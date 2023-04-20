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

namespace Zou\BannerSlider\Block\Adminhtml\Banner\Edit\Tab\Render;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;

/**
 * Class Type
 * @package Zou\BannerSlider\Block\Adminhtml\Banner\Edit\Tab\Render
 */
class Type extends AbstractRenderer
{
    /**
     * Render banner type
     *
     * @param DataObject $row
     *
     * @return string
     */
    public function render(DataObject $row)
    {
        $type = $row->getData($this->getColumn()->getIndex());
        switch ($type) {
            case 0:
                $type = 'Image';
                break;
            case 1:
                $type = 'Advanced';
                break;
        }

        return $type;
    }
}
