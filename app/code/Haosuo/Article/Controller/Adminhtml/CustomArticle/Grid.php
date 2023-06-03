<?php
namespace Haosuo\Article\Controller\Adminhtml\CustomArticle;

class Grid extends \Haosuo\Article\Controller\Adminhtml\CustomArticle
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultLayout = $this->_resultLayoutFactory->create();

        return $resultLayout;
    }
}
