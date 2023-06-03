<?php
//当前插件的帮助类，通用函数。
namespace Haosuo\Article\Helper;

use Magento\Customer\Model\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_storeManager;
    protected $_responseFactory;
    protected $_url;
    protected $_objectManager;
    protected $httpContext;
    protected $_coreRegistry;

    public function __construct(
        \Magento\Framework\App\Helper\Context  $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\Registry $registry
        ) {

            parent::__construct($context);
            $this->_responseFactory = $responseFactory;
            $this->_url = $context->getUrlBuilder();
            $this->scopeConfig = $context->getScopeConfig();
            $this->_objectManager = $objectManager;
            $this->httpContext = $httpContext;
            $this->_storeManager = $storeManager;
            $this->_coreRegistry = $registry;
    }


    public function isLoggedIn()
    {
        return $this->httpContext->getValue(Context::CONTEXT_AUTH);
    }

    /**
     * Get store config
     *
     * @param string $config
     */
    public function getConfig($config)
    {
        return $this->scopeConfig->getValue($config, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

}
