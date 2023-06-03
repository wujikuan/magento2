<?php
namespace Haosuo\Article\Controller\Adminhtml\CustomArticle;

class NewAction extends \Haosuo\Article\Controller\Adminhtml\CustomArticle
{
    public function execute()
    {
        $resultForward = $this->_resultForwardFactory->create();

        return $resultForward->forward('edit');
    }
}
