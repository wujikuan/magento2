<?php
namespace Haosuo\Aticle\Block\Adminhtml\CustomArticle\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('customarticle_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('CustomArticle Information'));
    }
}
