<?php

namespace Haosuo\Wechat\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Escaper;
use Magento\Payment\Helper\Data as PaymentHelper;

class WechatCheckoutConfigProvider implements ConfigProviderInterface
{
    /**
     * @var string[]
     */
    protected $methodCode = Wechat::PAYMENT_METHOD_CHECKMO_CODE;

    /**
     * @var Wechat
     */
    protected $method;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @param PaymentHelper $paymentHelper
     * @param Escaper $escaper
     */
    public function __construct(
        PaymentHelper $paymentHelper,
        Escaper $escaper
    ) {
        $this->escaper = $escaper;
        $this->method = $paymentHelper->getMethodInstance($this->methodCode);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return [
            'payment' => [
                'wechat' => [
                    'active' => true,
                    'config' =>array (
                        //应用ID,您的APPID。
                        'app_id' => "",
                        //商户私钥
                        'merchant_private_key' => "",
                        //异步通知地址
                        'notify_url' => "",
                        //同步跳转
                        'return_url' => "",
                        //编码格式
                        'charset' => "UTF-8",
                        //签名方式
                        'sign_type'=>"RSA2",
                        //网关
                        'gatewayUrl' => "",
                        // 公钥
                        'wechat_public_key' => "",
                        //日志路径
                        'log_path' => "",
                    )
                ]
            ],
        ];
    }

}
