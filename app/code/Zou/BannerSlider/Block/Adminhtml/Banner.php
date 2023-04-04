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

namespace Zou\BannerSlider\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

/**
 * Class Banner
 * @package Zou\BannerSlider\Block\Adminhtml
 */
class Banner extends Container
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller     = 'adminhtml_banner';
        $this->_blockGroup     = 'Zou_BannerSlider';
        $this->_headerText     = __('Banners');
        $this->_addButtonLabel = __('Create New Banner');

        parent::_construct();
    }
}
