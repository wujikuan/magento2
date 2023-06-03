<?php

namespace Haosuo\Article\Controller\Adminhtml\PhysicalStore;

use Magento\Framework\App\Filesystem\DirectoryList;

class ExportExcel extends  \Haosuo\Article\Controller\Adminhtml\CustomArticle
{
    public function execute()
    {
        $fileName = 'customArticle.xls';

        /** @var \\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $content = $resultPage->getLayout()->createBlock('Haosuo\Article\Block\Adminhtml\CustomArticle\Grid')->getExcel();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
