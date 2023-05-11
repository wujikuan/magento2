<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Haosuo\Alipay\Model;

use Exception;
use Haosuo\Alipay\Api\AlipayServiceInterface;
use Haosuo\Alipay\Model\AlipayTrade\AlipayConfig;
use Magento\Framework\UrlInterface;
use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Quote\Api\Data\PaymentMethodExtensionInterface;

use Haosuo\Alipay\Model\AlipayTrade\pagepay\service\AlipayTradeService;
use Haosuo\Alipay\Model\AlipayTrade\pagepay\buildermodel\AlipayTradePagePayContentBuilder;
use Psr\Log\LoggerInterface;


/**
 * Class Alipay
 *
 * @method PaymentMethodExtensionInterface getExtensionAttributes()
 *
 * @api
 * @since 100.0.2
 */
class AlipayService extends AbstractMethod implements AlipayServiceInterface
{
    const PAYMENT_METHOD_CHECKMO_CODE = 'alipay';

    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_CHECKMO_CODE;

    /**
     * @var string
     */
    protected $_formBlockType = \Haosuo\Alipay\Block\Form\Alipay::class;

    /**
     * @var string
     */
    protected $_infoBlockType = \Haosuo\Alipay\Block\Info\Alipay::class;


    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = true;
    function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        UrlInterface $urlBuilder,
    ){
        $this->_checkoutSession = $checkoutSession;
        $this->_urlBuilder = $urlBuilder;

    }


    /***
     * @return string
     */
    public function getOrderPlaceRedirectUrl()
    {

        $order = $this->_checkoutSession->getLastRealOrder();

        // 获取支付宝接口所需的参数
      /*  $params = [
            'app_id' => $this->appid,
            'method' => 'alipay.trade.page.pay',
            'charset' => 'utf-8',
            'sign_type' => 'RSA2',
            'timestamp' => '2023-05-09 18:24:40',
            'version' => '1.0',
            'notify_url' => $this->_urlBuilder->getUrl($this->notify_url),
            'return_url' => $this->_urlBuilder->getUrl($this->return_url),
            'format'=>'json',
            'alipay_sdk'=>'alipay-sdk-php-20161101',
            'biz_content' => json_encode([
                'out_trade_no' => $order->getIncrementId(),
                'product_code' => 'FAST_INSTANT_TRADE_PAY',
                'total_amount' => $order->getGrandTotal(),
                'subject' => 'Order ' . $order->getIncrementId(),
            ]),
        ];*/

        $config = AlipayConfig::getConfig();
        $totalPrice = $order->getGrandTotal();
        // 最少支付1分钱
        if ($totalPrice < 0.004){
            $totalPrice = 0.01;
        }

        //构造参数
        $payRequestBuilder = new AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody('Order ' . $order->getIncrementId());
        $payRequestBuilder->setSubject('Order ' . $order->getIncrementId());
        $payRequestBuilder->setTotalAmount(round($totalPrice,2));
        $payRequestBuilder->setOutTradeNo($order->getIncrementId());

      /*  $payRequestBuilder = new AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody('Order ' . 000000025);
        $payRequestBuilder->setSubject('Order ' . 000000025);
        $payRequestBuilder->setTotalAmount(33.53);
        $payRequestBuilder->setOutTradeNo(000000025);*/

        $aop = new AlipayTradeService($config);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
         */
        $return_url =  $this->_urlBuilder->getRouteUrl($config['return_url']);
        $notify_url =  $this->_urlBuilder->getRouteUrl($config['notify_url']);

        $response = $aop->pagePay($payRequestBuilder,$return_url,$notify_url);

        return $response;
    }



}


