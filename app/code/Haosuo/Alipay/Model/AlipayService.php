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
     * @var array
     */
    protected $config;

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
        $this->config = AlipayConfig::getConfig();
    }


    /***
     * @return string
     */
    public function getOrderPlaceRedirectUrl()
    {

        $order = $this->_checkoutSession->getLastRealOrder();

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

        $config = $this->config;
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


    /**
     * @return string Token created
     * @throws Exception
     */
    public function getNotifyAction()
    {
        $arr=$_POST;
        $return_url = $this->_urlBuilder->getRouteUrl($this->config['return_url']);
        $alipaySevice = new AlipayTradeService($this->config);
        $alipaySevice->writeLog('alipay_result_start');
        $alipaySevice->writeLog(var_export($_POST,true));
        $alipaySevice->writeLog('alipay_result_end');

        $result = $alipaySevice->check($arr);
    }

}


