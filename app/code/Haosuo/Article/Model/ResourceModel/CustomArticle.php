<?php

namespace Haosuo\Article\Model\ResourceModel;

class CustomArticle extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('custom_article','id');
    }
}
