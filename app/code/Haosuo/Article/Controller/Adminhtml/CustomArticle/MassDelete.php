<?php
namespace Haosuo\Article\Controller\Adminhtml\CustomArticle;

class MassDelete extends \Haosuo\Article\Controller\Adminhtml\CustomArticle
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $physicalStoreIds = $this->getRequest()->getParam('customArticle');
        if (!is_array($physicalStoreIds) || empty($physicalStoreIds)) {
            $this->messageManager->addError(__('Please select customArticle(s).'));
        } else {
            $customArticleCollection = $this->_customArticleCollectionFactory->create()
                ->addFieldToFilter('id', ['in' => $physicalStoreIds]);
            try {
                foreach ($customArticleCollection as $customArticle) {
                    $customArticle->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($physicalStoreIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
