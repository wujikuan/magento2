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

namespace Zou\BannerSlider\Model\ResourceModel\Banner;

use Magento\Framework\DB\Select;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Zend_Db_Select;

/**
 * Class Collection
 * @package Zou\BannerSlider\Model\ResourceModel\Banner
 */
class Collection extends AbstractCollection
{
    /**
     * ID Field Name
     *
     * @var string
     */
    protected $_idFieldName = 'banner_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'zou_bannerslider_banner_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'banner_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Zou\BannerSlider\Model\Banner', 'Zou\BannerSlider\Model\ResourceModel\Banner');
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @return Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);

        return $countSelect;
    }

    /**
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     *
     * @return array
     */
    protected function _toOptionArray($valueField = 'banner_id', $labelField = 'name', $additional = [])
    {
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }

    /**
     * @param array|string $field
     * @param null $condition
     *
     * @return AbstractCollection
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field === 'banner_id') {
            $field = 'main_table.banner_id';
        }

        if ($field === 'type' && isset($condition['like'])) {
            $condition['like'] = str_replace("'%", '', $condition['like']);
            $condition['like'] = str_replace("%'", '', $condition['like']);
            if (stripos('Image', $condition['like']) !== false) {
                $condition = 0;
            }
            if (stripos('Advanced', $condition['like']) !== false) {
                $condition = 1;
            }
        }

        if ($field === 'status' && isset($condition['like'])) {
            $condition['like'] = str_replace("'%", '', $condition['like']);
            $condition['like'] = str_replace("%'", '', $condition['like']);
            if (stripos('Enable', $condition['like']) !== false) {
                $condition = 1;
            }
            if (stripos('Disable', $condition['like']) !== false) {
                $condition = 0;
            }
        }

        return parent::addFieldToFilter($field, $condition); // TODO: Change the autogenerated stub
    }
}
