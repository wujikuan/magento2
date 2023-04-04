<?php
/**
 * Zou
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Zou.com license that is
 * available through the world-wide-web at this URL:
 * https://bbs.mallol.cn/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Zou
 * @package     Zou_BannerSlider
 * @copyright   Copyright (c) Zou (https://bbs.mallol.cn/)
 * @license     https://bbs.mallol.cn/LICENSE.txt
 */

namespace Zou\BannerSlider\Controller\Adminhtml\Banner;

use Exception;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Zou\BannerSlider\Controller\Adminhtml\Banner;

/**
 * Class Delete
 * @package Zou\BannerSlider\Controller\Adminhtml\Banner
 */
class Delete extends Banner
{
    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            $this->bannerFactory->create()
                ->load($this->getRequest()->getParam('banner_id'))
                ->delete();
            $this->messageManager->addSuccess(__('The Banner has been deleted.'));
        } catch (Exception $e) {
            // display error message
            $this->messageManager->addErrorMessage($e->getMessage());
            // go back to edit form
            $resultRedirect->setPath(
                'mpbannerslider/*/edit',
                ['banner_id' => $this->getRequest()->getParam('banner_id')]
            );

            return $resultRedirect;
        }

        $resultRedirect->setPath('mpbannerslider/*/');

        return $resultRedirect;
    }
}
