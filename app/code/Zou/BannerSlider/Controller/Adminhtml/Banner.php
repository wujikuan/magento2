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
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Registry;
use Zou\BannerSlider\Model\BannerFactory;

/**
 * Class Banner
 * @package Zou\BannerSlider\Controller\Adminhtml
 */
abstract class Banner extends Action
{
    /**
     * Banner Factory
     *
     * @var BannerFactory
     */
    protected $bannerFactory;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * Result redirect factory
     *
     * @var RedirectFactory
     */

    /**
     * constructor
     *
     * @param BannerFactory $bannerFactory
     * @param Registry $coreRegistry
     * @param Context $context
     */
    public function __construct(
        BannerFactory $bannerFactory,
        Registry $coreRegistry,
        Context $context
    ) {
        $this->bannerFactory = $bannerFactory;
        $this->coreRegistry  = $coreRegistry;

        parent::__construct($context);
    }

    /**
     * Init Banner
     *
     * @return \Zou\BannerSlider\Model\Banner
     */
    protected function initBanner()
    {
        $bannerId = (int) $this->getRequest()->getParam('banner_id');
        /** @var \Zou\BannerSlider\Model\Banner $banner */
        $banner = $this->bannerFactory->create();
        if ($bannerId) {
            $banner->load($bannerId);
        }
        $this->coreRegistry->register('mpbannerslider_banner', $banner);

        return $banner;
    }
}
