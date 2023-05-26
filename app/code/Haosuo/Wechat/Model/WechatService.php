<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Haosuo\Wechat\Model;


use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Haosuo\Wechat\Helper\Data;
use Haosuo\Wechat\Api\WechatServiceInterface;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\UrlInterface;
use Haosuo\Wechat\Model\WechatPayMethod;

/**
 * Class Wechat
 *
 * @method PaymentMethodExtensionInterface getExtensionAttributes()
 *
 * @api
 * @since 100.0.2
 */
class WechatService  implements WechatServiceInterface
{
    /**
     * Payment method code
     *
     * @var string
     */

    /**
     * @var string
     */
    protected $_formBlockType = \Haosuo\Wechat\Block\Form\Wechat::class;

    /**
     * @var string
     */
    protected $_infoBlockType = \Haosuo\Wechat\Block\Info\Wechat::class;
    /**
     * @var array
     */
//    protected $config;
    protected $_dataHelper;
    protected $_wechatPayMethod;
    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = true;
    protected $imageRenderer;

    protected $writer;
    protected $rendererStyle;
    protected $imagickImageBackEnd;

    function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        UrlInterface $urlBuilder,
        Data $dataHelper,
        WechatPayMethod $wechatPayMethod,
    ){
        $this->_checkoutSession = $checkoutSession;
        $this->_urlBuilder = $urlBuilder;
        $this->_dataHelper = $dataHelper;
        $this->_wechatPayMethod =$wechatPayMethod;

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
//        $payRequestBuilder->setBody('Order ' . $order->getIncrementId());
//        $payRequestBuilder->setSubject('Order ' . $order->getIncrementId());
//        $payRequestBuilder->setTotalAmount(round($totalPrice,2));
//        $payRequestBuilder->setOutTradeNo($order->getIncrementId());
//        $res = $this->_wechatPayMethod->native(
//            $order->getIncrementId(),
//            $order->getIncrementId(),
//            round($totalPrice,2)
//        );

        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new ImagickImageBackEnd()
        );

        $writer = new Writer($renderer);
        // $writer->writeFile('Hello World!', 'qrcode.png');
        $root =$_SERVER['DOCUMENT_ROOT'] ;
        $path = '/static/frontend/tem/qrcode/';
        if (!is_dir($root.$path)){
            mkdir(
                $root.$path,
                0775,
                true
            );
        }
        // $tempName = $order->getIncrementId().time().'.png';
         $tempName = '11122323434.png';
        $writer->writeFile('Hello World!', $root.$path.$tempName);

        return $path.$tempName;
    }

    /**
     * @return true
     */
    public function getOrderPaymentStatus(){
        $order = $this->_checkoutSession->getLastRealOrder();
        if ($order){
            if ($order->getStatus() == 'processing'){
                return true;
            }else{
                return false;
            }
        }else{
            // throw new \Magento\Framework\Exception\LocalizedException(__('The Order Not Found'));
            return false;
        }
    }

}


