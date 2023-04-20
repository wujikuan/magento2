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

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Zou\BannerSlider\Controller\Adminhtml\Banner;
use Zou\BannerSlider\Model\BannerFactory;

/**
 * Class Edit
 * @package Zou\BannerSlider\Controller\Adminhtml\Banner
 */
class Edit extends Banner
{
    const ADMIN_RESOURCE = 'Zou_BannerSlider::banner';

    /**
     * Page factory
     *
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Edit constructor.
     *
     * @param PageFactory $resultPageFactory
     * @param BannerFactory $bannerFactory
     * @param Registry $registry
     * @param Context $context
     */
    public function __construct(
        PageFactory $resultPageFactory,
        BannerFactory $bannerFactory,
        Registry $registry,
        Context $context
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($bannerFactory, $registry, $context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|ResponseInterface|Redirect|ResultInterface|Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('banner_id');
        /** @var \Zou\BannerSlider\Model\Banner $banner */
        $banner = $this->initBanner();

        if ($id) {
            $banner->load($id);
            if (!$banner->getId()) {
                $this->messageManager->addError(__('This Banner no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath(
                    'mpbannerslider/*/edit',
                    [
                        'banner_id' => $banner->getId(),
                        '_current'  => true
                    ]
                );

                return $resultRedirect;
            }
        }

        $data = $this->_session->getData('mpbannerslider_banner_data', true);
        if (!empty($data)) {
            $banner->setData($data);
        }

        /** @var \Magento\Backend\Model\View\Result\Page|Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Zou_BannerSlider::banner');
        $resultPage->getConfig()->getTitle()
            ->set(__('Banners'))
            ->prepend($banner->getId() ? $banner->getName() : __('New Banner'));

        return $resultPage;
    }
}
