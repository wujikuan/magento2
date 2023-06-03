<?php
namespace Haosuo\Article\Block;

class CustomArticles extends \Magento\Framework\View\Element\Template
{
    protected $_dataHelper;

    protected $_customArticle;


    protected $scopeConfig;
    protected $_storeManager;
    protected $_countryCollection;
    protected $collection;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Haosuo\Article\Helper\Data $dataHelper
     * @param \Haosuo\Article\Model\CustomArticle $customArticle
     * @param \Magento\Directory\Model\ResourceModel\Country\Collection $countryCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context  $context,
        \Haosuo\Article\Helper\Data $dataHelper,
        \Haosuo\Article\Model\CustomArticle $customArticle,
        \Magento\Directory\Model\ResourceModel\Country\Collection $countryCollection,
        array $data = []
        ) {
            $this->_dataHelper = $dataHelper;
            $this->_customArticle = $customArticle;
            $this->scopeConfig = $context->getScopeConfig();
            $this->_storeManager = $context->getStoreManager();
            $this->_countryCollection =$countryCollection;
            parent::__construct($context, $data);
    }

    public function getPhysicalStores()
    {
        $collection = $this->_customArticle->getCollection();
        $collection->addFieldToFilter('status',1);
        $collection->setOrder('title','asc');
        $physicalStores = [];
        foreach ($collection as $_store){
            $physicalStores[] = $_store;
        }
        usort($physicalStores, array($this, '_sortByName'));

        return $physicalStores;
    }

    public function _sortByName($a, $b)
    {
        $x = trim($a->getName());
        $y = trim($b->getName());
        if ($x == '') return 1;
        if ($y == '') return -1;
        return strnatcmp($x, $y);
    }

    public function getMediaUrl(){
        $mediaUrl = $this->_context->getStoreManager()->getStore()->getBaseUrl('media');
        return $mediaUrl;
    }

    public function getConfig($key, $store = null)
    {
        $store = $this->_storeManager->getStore($store);
        $websiteId = $store->getWebsiteId();

        $result = $this->scopeConfig->getValue(
            $key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store);
        return $result;
    }

    public function getCountry($foregroundCountries='')
    {
        $collection=$this->_countryCollection->loadData()->setForegroundCountries(
            $foregroundCountries
            )->toOptionArray(
                false
                );
        return $collection;
    }


}
