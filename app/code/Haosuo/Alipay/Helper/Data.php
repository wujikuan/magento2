<?php

namespace Haosuo\Alipay\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Payment\Model\MethodInterface;
use Magento\Store\Model\ScopeInterface;

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
    const XML_PATH_NOTIFY_URL = 'payment/alipay/notify_url';
    const XML_PATH_RETURN_URL = 'payment/alipay/return_url';
    const XML_PATH_GATEWAY_URL = 'payment/alipay/gateway_url';

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
        return $this->scopeConfig->getValue(
            self::XML_PATH_NOTIFY_URL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getReturnUrl($storeId = null){
        return $this->scopeConfig->getValue(
            self::XML_PATH_RETURN_URL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getGatewayUrl($storeId = null){
        return $this->scopeConfig->getValue(
            self::XML_PATH_GATEWAY_URL,
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


}
