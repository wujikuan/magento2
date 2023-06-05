<?php
namespace Haosuo\Article\Controller\Adminhtml\CustomArticle;


use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Haosuo\Article\Controller\Adminhtml\CustomArticle
{


    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $formPostValues = $this->getRequest()->getPostValue();
        if ($formPostValues) {
            $customArticleData = $formPostValues;

            $customArticleId = isset($customArticleData['id']) ? $customArticleData['id'] : null;

            $imageRequest = $this->getRequest()->getFiles('image');
            if ($imageRequest) {
                if (isset($imageRequest['name'])) {
                    $fileName = $imageRequest['name'];
                } else {
                    $fileName = '';
                }
            } else {
                $fileName = '';
            }

            if ($imageRequest && strlen($fileName)) {
                /*
                 * Save image uploadl
                 */
                try {
                    $uploader = $this->_objectManager->create(
                        'Magento\MediaStorage\Model\File\Uploader',
                        ['fileId' => 'image']
                        );
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);

                    $imageAdapter = $this->_adapterFactory;

                    $uploader->addValidateCallback('image', $imageAdapter, 'validateUploadFile');
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);

                    /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */

                    $mediaDirectory = $this->_fileSystem->getDirectoryRead(DirectoryList::MEDIA);
                    $result = $uploader->save(
                        $mediaDirectory->getAbsolutePath(\Haosuo\Article\Model\CustomArticle::BASE_MEDIA_PATH)
                        );
                    $customArticleData['image'] = \Haosuo\Article\Model\CustomArticle::BASE_MEDIA_PATH.$result['file'];
                } catch (\Exception $e) {
                    if ($e->getCode() == 0) {
                        $this->messageManager->addError($e->getMessage());
                    }
                }
            } else {
                if (isset($customArticleData['image']) && isset($customArticleData['image']['value'])) {
                    if (isset($customArticleData['image']['delete'])) {
                        $customArticleData['image'] = null;
                        $customArticleData['delete_image'] = true;
                    } elseif (isset($customArticleData['image']['value'])) {
                        $customArticleData['image'] = $customArticleData['image']['value'];
                    } else {
                        $customArticleData['image'] = null;
                    }
                }
            }



            $model = $this->_customArticleFactory->create();
            $model->load($customArticleId);
            $model->setData($customArticleData);
            try {
                $model->save();
                $this->messageManager->addSuccess(__('The customArticle has been saved.'));
                $this->_getSession()->setFormData(false);

                return $this->_getBackResultRedirect($resultRedirect, $model->getId());
            } catch (\Exception $e) {
//                 echo $e->getMessage();die;
                $this->messageManager->addError($e->getMessage());
                $this->messageManager->addException($e, __('Something went wrong while saving the customArticle.'));
            }

            $this->_getSession()->setFormData($formPostValues);

            return $resultRedirect->setPath('*/*/edit', [static::PARAM_CRUD_ID => $customArticleId]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
