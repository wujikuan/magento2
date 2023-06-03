<?php
namespace Haosuo\Article\Controller\Adminhtml\CustomArticle;


use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Export Csv action
 * @category Iggo
 * @package  Zou_Demo
 * @module   PhysicalStores
 * @author   Iggo Developer
 */
class ExportCsv extends \Haosuo\Article\Controller\Adminhtml\CustomArticle
{
    public function execute()
    {
        $fileName = 'customArticle.csv';

        /** @var \\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $content = $resultPage->getLayout()->createBlock('Haosuo\Article\Block\Adminhtml\CustomArticle\Grid')->getCsv();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
