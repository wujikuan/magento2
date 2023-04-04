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

/**
 * Class Type
 * @package Zou\BannerSlider\Model\Config\Source
 */
class Type implements ArrayInterface
{
    const IMAGE   = '0';
    const CONTENT = '1';

    /**
     * to option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'value' => self::IMAGE,
                'label' => __('Image')
            ],
            [
                'value' => self::CONTENT,
                'label' => __('Advanced')
            ]
        ];

        return $options;
    }
}
