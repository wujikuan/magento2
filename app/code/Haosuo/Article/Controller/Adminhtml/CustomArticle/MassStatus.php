<?php
namespace Haosuo\Article\Controller\Adminhtml\CustomArticle;

class MassStatus extends \Haosuo\Article\Controller\Adminhtml\CustomArticle
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $customArticleIds = $this->getRequest()->getParam('customArticle');
        $status = $this->getRequest()->getParam('status');
        if (!is_array($customArticleIds) || empty($customArticleIds)) {
            $this->messageManager->addError(__('Please select customArticle(s).'));
        } else {
            try {
                $customArticleCollection = $this->_customArticleCollectionFactory->create()
                    ->addFieldToFilter('id', ['in' => $customArticleIds]);

                foreach ($customArticleCollection as $article) {
                    $article->setStatus($status)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been changed status.', count($customArticleIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
