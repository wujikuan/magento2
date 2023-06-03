<?php

namespace Haosuo\Article\Controller\Adminhtml\CustomArticle;

use Magento\Framework\App\Filesystem\DirectoryList;

class ExportXml extends \Haosuo\Article\Controller\Adminhtml\CustomArticle
{
    public function execute()
    {
        $fileName = 'customArticle.xml';

        /** @var \\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $content = $resultPage->getLayout()->createBlock('Haosuo\Article\Block\Adminhtml\CustomArticle\Grid')->getXml();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
