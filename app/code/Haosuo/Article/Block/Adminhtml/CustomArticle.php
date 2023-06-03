<?php
namespace Haosuo\Article\Block\Adminhtml;

class CustomArticle extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor.
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_customArticle';
        $this->_blockGroup = 'Haosuo_Article';
        $this->_headerText = __('CustomArticles');
        $this->_addButtonLabel = __('Add New Article');

        parent::_construct();
    }
}
