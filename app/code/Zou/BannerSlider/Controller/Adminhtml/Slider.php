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

namespace Zou\BannerSlider\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Zou\BannerSlider\Model\SliderFactory;

/**
 * Class Slider
 * @package Zou\BannerSlider\Controller\Adminhtml
 */
abstract class Slider extends Action
{
    /**
     * Slider Factory
     *
     * @var SliderFactory
     */
    protected $sliderFactory;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * Slider constructor.
     *
     * @param SliderFactory $sliderFactory
     * @param Registry $coreRegistry
     * @param Context $context
     */
    public function __construct(
        SliderFactory $sliderFactory,
        Registry $coreRegistry,
        Context $context
    ) {
        $this->sliderFactory = $sliderFactory;
        $this->coreRegistry  = $coreRegistry;

        parent::__construct($context);
    }

    /**
     * Init Slider
     *
     * @return \Zou\BannerSlider\Model\Slider
     */
    protected function initSlider()
    {
        $sliderId = (int) $this->getRequest()->getParam('slider_id');
        /** @var \Zou\BannerSlider\Model\Slider $slider */
        $slider = $this->sliderFactory->create();
        if ($sliderId) {
            $slider->load($sliderId);
        }
        $this->coreRegistry->register('mpbannerslider_slider', $slider);

        return $slider;
    }
}
