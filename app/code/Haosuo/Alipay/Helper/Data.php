<?php

namespace Haosuo\Alipay\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Payment\Model\MethodInterface;
use Magento\Store\Model\ScopeInterface;
use UnexpectedValueException;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_PAYMENT_METHODS = 'alipay';

    public function __construct(
        Context $context,
        TimezoneInterface $timezone,
    )
    {
        parent::__construct($context);

        $this->timezone = $timezone;
    }



    const XML_PATH_APP_ID = 'payment/alipay/app_id';
    const XML_PATH_MERCHANT_PRIVATE_KEY = 'payment/alipay/merchant_private_key';
    const XML_PATH_ALIPAY_PUBLIC_KEY = 'payment/alipay/alipay_public_key';
    const XML_PATH_MODAL_DEBUG = 'payment/alipay/modal_debug';
    const XML_PATH_NOTIFY_URL = 'alipay/alipay/notify';
    const XML_PATH_RETURN_URL = 'checkout/onepage/success';
    const XML_PATH_GATEWAY_URL = 'https://openapi.alipaydev.com/gateway.do';

    public function getAppId($storeId = null){
        return $this->scopeConfig->getValue(
            self::XML_PATH_APP_ID,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getMerchantPrivateKey($storeId = null){
        return $this->scopeConfig->getValue(
            self::XML_PATH_MERCHANT_PRIVATE_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
    public function getAlipayPublicKey($storeId = null){
        return $this->scopeConfig->getValue(
            self::XML_PATH_ALIPAY_PUBLIC_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getNotifyUrl($storeId = null){
        return self::XML_PATH_NOTIFY_URL;
    }

    public function getReturnUrl($storeId = null){
        return self::XML_PATH_RETURN_URL;
    }

    public function getGatewayUrl($storeId = null){
        return self::XML_PATH_GATEWAY_URL;
    }

    public function getModalDebug($storeId = null){
        return $this->scopeConfig->getValue(
            self::XML_PATH_MODAL_DEBUG,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get config name of method model
     *
     * @param string $code
     * @return string
     */
    protected function getMethodModelConfigName($code)
    {
        return sprintf('%s/%s/model', self::XML_PATH_PAYMENT_METHODS, $code);
    }

    /**
     * Retrieve method model object
     *
     * @param string $code
     *
     * @return MethodInterface
     * @throws LocalizedException
     */
    public function getMethodInstance($code)
    {
        $class = $this->scopeConfig->getValue(
            $this->getMethodModelConfigName($code),
            ScopeInterface::SCOPE_STORE
        );

        if (!$class) {
            throw new UnexpectedValueException('Payment model name is not provided in config!');
        }

        return $this->_methodFactory->create($class);
    }

    /**
     * 请确保项目文件有可写权限，不然打印不了日志。
     */
    public function writeLog($text) {
        if ($this->getModalDebug()){
            $this->_logger->debug(date ( "Y-m-d H:i:s" ) . "  " . $text . "\r\n");
        }
    }
}
