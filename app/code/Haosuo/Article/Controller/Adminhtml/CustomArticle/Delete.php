<?php
namespace Haosuo\Article\Controller\Adminhtml\CustomArticle;

class Delete extends \Haosuo\Article\Controller\Adminhtml\CustomArticle
{
    public function execute()
    {
        $customArticleId = $this->getRequest()->getParam(static::PARAM_CRUD_ID);
        try {
            $physicalStore = $this->_customArticleFactory->create()->setId($customArticleId);
            $physicalStore->delete();
            $this->messageManager->addSuccess(
                __('Delete successfully !')
            );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
