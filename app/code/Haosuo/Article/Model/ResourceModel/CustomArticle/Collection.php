<?php

namespace Haosuo\Article\Model\ResourceModel\CustomArticle;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Haosuo\Article\Model\CustomArticle','Haosuo\Article\Model\ResourceModel\CustomArticle');
    }
}
