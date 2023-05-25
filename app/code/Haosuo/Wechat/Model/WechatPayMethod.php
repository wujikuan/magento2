<?php

namespace Haosuo\Wechat\Model;

use Haosuo\Wechat\Helper\Data;

use WeChatPay\Builder;
use WeChatPay\Crypto\Rsa;
use WeChatPay\Util\PemUtil;

class WechatPayMethod
{
    protected $appId;
    protected $merchantId;

    protected $instance;

    protected $notifyUrl;

    protected $returnUrl;

    protected $apiKey;
    const AUTH_TAG_LENGTH_BYTE = 16;

    public function __construct(
        Data $dataHelper
    ){
        // 应用appId
        $this->appId = $dataHelper->getAppId();
        // 商户号
        $this->merchantId = $dataHelper->getMerchantId();

        // 从本地文件中加载「商户API私钥」，「商户API私钥」会用来生成请求的签名
        $merchantPrivateKeyFilePath = $dataHelper->getMerchantPrivateKeyFilePath();
        if (!empty($merchantPrivateKeyFilePath)){
            $merchantPrivateKeyInstance = Rsa::from($merchantPrivateKeyFilePath, Rsa::KEY_TYPE_PRIVATE);
        }
        // 「商户API证书」的「证书序列号」
        $merchantCertificateSerial = $dataHelper->getMerchantCertificateSerial();
        // 从本地文件中加载「微信支付平台证书」，用来验证微信支付应答的签名
        $platformCertificateFilePath = $dataHelper->getPlatformCertificateFilePath();
        if ($platformCertificateFilePath){
            $platformPublicKeyInstance = Rsa::from($platformCertificateFilePath, Rsa::KEY_TYPE_PUBLIC);
            // 从「微信支付平台证书」中获取「证书序列号」
            $platformCertificateSerial = PemUtil::parseCertificateSerialNo($platformCertificateFilePath);
        }


        $this->notifyUrl = $dataHelper->getNotifyUrl();
        $this->returnUrl = $dataHelper->getReturnUrl();

        $this->apiKey = $dataHelper->getApiKey();
        if (
            isset($merchantPrivateKeyInstance) &&
            isset($platformCertificateSerial) &&
            isset($platformPublicKeyInstance) &&
            isset($this->merchantId) &&
            isset($merchantCertificateSerial)
        ){
            $this->instance = Builder::factory(
                [
                    'mchid'      => $this->merchantId,
                    'serial'     => $merchantCertificateSerial,
                    'privateKey' => $merchantPrivateKeyInstance,
                    'certs'      => [
                        $platformCertificateSerial => $platformPublicKeyInstance,
                    ]
                ]
            );
        }
    }

    /**
     * @param $outTradeNo
     * @param $description
     * @param $amount
     * @param $currency
     * @return void
     */
    public function native($outTradeNo,$description,$amount,$currency='CNY'){
        if (empty($this->instance) || $this->appId){
            throw new \Magento\Framework\Exception\LocalizedException(__('Payment Setting Error'));
        }
        try {
            $resp = $this->instance
                ->chain('v3/pay/transactions/native')
                ->post(['json' => [
                    'mchid'        => $this->merchantId,
                    'out_trade_no' => $outTradeNo,
                    'appid'        => $this->appId,
                    'description'  => $description,
                    'notify_url'   => $this->notifyUrl,
                    'amount'       => [
                        'total'    => $amount * 100, // 单位（分）
                        'currency' => $currency
                    ],
                ]]);
            // 处理响应

            if ($resp->getStatusCode() == 200) {
                $body = $resp->getBody()->getContents();
                $xml = simplexml_load_string($body);
                if ($xml->return_code == 'SUCCESS') {
                    $code_url = $xml->code_url;
                    // 在HTML页面中显示二维码
                    return 'https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl=' . urlencode($code_url);
                } else {
                    // echo '支付请求失败：' . $xml->return_msg. '</br>';
                    throw new \Magento\Framework\Exception\LocalizedException(__('Payment Fail ：HTTP ' . $xml->return_msg));
                }
            } else {
               // echo '支付请求失败：HTTP ' . $resp->getStatusCode() . '</br>';
                throw new \Magento\Framework\Exception\LocalizedException(__('Payment Fail ：HTTP ' . $resp->getStatusCode()));
            }
        }catch (\Exception $e){
            // 进行错误处理
//            echo $e->getMessage(), PHP_EOL;
//            if ($e instanceof \GuzzleHttp\Exception\RequestException && $e->hasResponse()) {
//                $r = $e->getResponse();
//                echo $r->getStatusCode() . ' ' . $r->getReasonPhrase(), PHP_EOL;
//                echo $r->getBody(), PHP_EOL, PHP_EOL, PHP_EOL;
//            }
//            echo $e->getTraceAsString(), PHP_EOL;
            throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()));
        }
    }

    /**
     * Decrypt AEAD_AES_256_GCM ciphertext
     *
     * @param string $associatedData     AES GCM additional authentication data
     * @param string $nonceStr           AES GCM nonce
     * @param string $ciphertext         AES GCM cipher text
     *
     * @return string|bool      Decrypted string on success or FALSE on failure
     */
    public function decryptToString(string $associatedData, string $nonceStr, string $ciphertext)
    {

        if (empty($this->apiKey)){
            if (empty($this->instance)){
                throw new \Magento\Framework\Exception\LocalizedException(__('Payment Setting apiKey Error'));
            }
        }
        $ciphertext = \base64_decode($ciphertext);
        if (strlen($ciphertext) <= self::AUTH_TAG_LENGTH_BYTE) {
            return false;
        }
        $ctext = substr($ciphertext, 0, -self::AUTH_TAG_LENGTH_BYTE);
        $authTag = substr($ciphertext, -self::AUTH_TAG_LENGTH_BYTE);
        return \openssl_decrypt($ctext, 'aes-256-gcm', $this->apiKey, \OPENSSL_RAW_DATA, $nonceStr,
            $authTag, $associatedData);
    }


    public function refund($transactionId,$outRefundNo,$refundAmount,$totalAmount,$currency='CNY'){
        return true;

        if (empty($this->instance)){
            throw new \Magento\Framework\Exception\LocalizedException(__('Payment Setting Error'));
        }
        $promise = $this->instance
            ->chain('v3/refund/domestic/refunds')
            ->postAsync([
                'json' => [
                    'transaction_id' => $transactionId,
                    'out_refund_no'  => $outRefundNo,
                    'amount'         => [
                        'refund'   => round($refundAmount*100),
                        'total'    => round($totalAmount*100),
                        'currency' => $currency,
                    ],
                ],
            ])
            ->then(static function($response) {
                // 正常逻辑回调处理
                echo $response->getBody(), PHP_EOL;
                return $response;
            })
            ->otherwise(static function($e) {
                // 异常错误处理
                echo $e->getMessage(), PHP_EOL;
                if ($e instanceof \GuzzleHttp\Exception\RequestException && $e->hasResponse()) {
                    $r = $e->getResponse();
                    echo $r->getStatusCode() . ' ' . $r->getReasonPhrase(), PHP_EOL;
                    echo $r->getBody(), PHP_EOL, PHP_EOL, PHP_EOL;
                    throw new \Magento\Framework\Exception\LocalizedException(__($r->getStatusCode() . ' ' . $r->getReasonPhrase()));
                }
                echo $e->getTraceAsString(), PHP_EOL;
            });
        // 同步等待
        $promise->wait();
    }
}
