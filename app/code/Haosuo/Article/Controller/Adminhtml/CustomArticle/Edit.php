<?php

namespace Haosuo\Article\Controller\Adminhtml\CustomArticle;

class Edit extends \Haosuo\Article\Controller\Adminhtml\CustomArticle
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $id = $this->getRequest()->getParam('id');
        $model = $this->_customArticleFactory->create();
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This physicalStore no longer exists.'));

                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->_coreRegistry->register('customArticle', $model);

        return $resultPage;
    }
}
