<?php

namespace Haosuo\Wechat\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Payment\Model\MethodInterface;
use Magento\Store\Model\ScopeInterface;
use UnexpectedValueException;


class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_PAYMENT_METHODS = 'wechat';
    protected $_logger;

    public function __construct(
        Context $context,
        TimezoneInterface $timezone,
        \Psr\Log\LoggerInterface $logger
    )
    {
        parent::__construct($context);

        $this->timezone = $timezone;
        $this->_logger = $logger;
    }



    // 应用APPID
    const XML_PATH_APP_ID = 'payment/wechat/app_id';
    // 商户号
    const XML_PATH_MERCHANT_ID = 'payment/wechat/merchant_id';
    // 从本地文件中加载「商户API私钥」，「商户API私钥」会用来生成请求的签名
    const XML_PATH_MERCHANT_PRIVATE_KEY_FILE_PATH = 'payment/wechat/merchant_private_key_file_path';
    // 「商户API证书」的「证书序列号」
    const XML_PATH_MERCHANT_CERTIFICATE_SERIAL = 'payment/wechat/merchant_certificate_serial';
    // 微信支付平台证书
    const XML_PATH_PLATFORM_CERTIFICATE_FILE_PATH = 'payment/wechat/platform_certificate_file_path';
    const XML_PATH_NOTIFY_URL = 'wechat/wechat/notify';
    const XML_PATH_RETURN_URL = 'checkout/onepage/success';
    const XML_PATH_API_KEY = 'payment/wechat/api_key';
    const XML_PATH_MODAL_DEBUG = 'payment/wechat/modal_debug';

    public function getAppId($storeId = null){
        return $this->scopeConfig->getValue(
            self::XML_PATH_APP_ID,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getMerchantId($storeId = null){
        return $this->scopeConfig->getValue(
            self::XML_PATH_MERCHANT_ID,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getMerchantPrivateKeyFilePath($storeId = null){
        return $this->scopeConfig->getValue(
            self::XML_PATH_MERCHANT_PRIVATE_KEY_FILE_PATH,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
    public function getMerchantCertificateSerial($storeId = null){
        return $this->scopeConfig->getValue(
            self::XML_PATH_MERCHANT_CERTIFICATE_SERIAL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getPlatformCertificateFilePath($storeId = null){
        return $this->scopeConfig->getValue(
            self::XML_PATH_PLATFORM_CERTIFICATE_FILE_PATH,
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

    public function getApiKey($storeId = null){
        return $this->scopeConfig->getValue(
            self::XML_PATH_API_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
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
