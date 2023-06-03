<?php

namespace Haosuo\Article\Model;

class CustomArticle extends \Magento\Framework\Model\AbstractModel
{
    //定义实体店图片存放路径，放在pub/media/zou/physical_stores下面
    const BASE_MEDIA_PATH = 'article/custom_article';
    protected $_countryCollection;
    protected $_countryOptions;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ResourceModel\CustomArticle $resource
     * @param ResourceModel\CustomArticle\Collection $resourceCollection
     * @param \Magento\Directory\Model\ResourceModel\Country\Collection $countryCollection
     */
    //构造函数，传递PhysicalStore和PhysicalStore\Collection 两个参数，实例化
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Haosuo\Article\Model\ResourceModel\CustomArticle $resource,
        \Haosuo\Article\Model\ResourceModel\CustomArticle\Collection $resourceCollection,
        \Magento\Directory\Model\ResourceModel\Country\Collection $countryCollection
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection
        );
        $this->_countryCollection = $countryCollection;
    }



    //自定义函数 获取国家列表
    public function getAvailableCountry($isMultiselect = false, $foregroundCountries ='')
    {
        $this->_countryOptions = $this->_countryCollection->loadData()->setForegroundCountries(
            $foregroundCountries
        )->toOptionArray(
            false
        );
        $options = $this->_countryOptions;
        if (!$isMultiselect) {
            array_unshift($options, ['value' => '', 'label' => __('--Please Select--')]);
        }

        return $options;
    }

}
