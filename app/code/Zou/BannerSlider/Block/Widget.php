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

namespace Zou\BannerSlider\Block;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Widget\Block\BlockInterface;

/**
 * Class Widget
 * @package Zou\BannerSlider\Block
 */
class Widget extends Slider implements BlockInterface
{
    /**
     * @return array|AbstractCollection
     * @throws NoSuchEntityException
     */
    public function getBannerCollection()
    {
        $sliderId = $this->getData('slider_id');
        if (!$sliderId || !$this->helperData->isEnabled()) {
            return [];
        }

        $sliderCollection = $this->helperData->getActiveSliders();
        $slider           = $sliderCollection->addFieldToFilter('slider_id', $sliderId)->getFirstItem();
        $this->setSlider($slider);

        return parent::getBannerCollection();
    }
}
