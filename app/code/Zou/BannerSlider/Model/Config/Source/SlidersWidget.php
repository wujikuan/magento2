<?php
/**
 * Zou
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Zou.com license that is
 * available through the world-wide-web at this URL:
 * https://bbs.mallol.cn/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Zou
 * @package     Zou_BannerSlider
 * @copyright   Copyright (c) Zou (https://bbs.mallol.cn/)
 * @license     https://bbs.mallol.cn/LICENSE.txt
 */

namespace Zou\BannerSlider\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Zou\BannerSlider\Model\ResourceModel\Slider\CollectionFactory;

/**
 * Class SlidersWidget
 * @package Zou\BannerSlider\Model\Config\Source
 */
class SlidersWidget implements ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * SlidersWidget constructor.
     *
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ($this->toArray() as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $options;
    }

    /**
     * @return array
     */
    protected function toArray()
    {
        $options = [];
        $rules   = $this->collectionFactory->create()
            ->addActiveFilter()
            ->addFieldToFilter('location', ['finset' => Location::USING_SNIPPET_CODE]);

        foreach ($rules as $rule) {
            $options[$rule->getId()] = $rule->getName();
        }

        return $options;
    }
}
