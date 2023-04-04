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

namespace Zou\BannerSlider\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Zou\BannerSlider\Controller\Adminhtml\Slider;
use Zou\BannerSlider\Model\SliderFactory;

/**
 * Class Edit
 * @package Zou\BannerSlider\Controller\Adminhtml\Slider
 */
class Edit extends Slider
{
    const ADMIN_RESOURCE = 'Zou_BannerSlider::slider';

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
     * @param SliderFactory $sliderFactory
     * @param Registry $registry
     * @param Context $context
     */
    public function __construct(
        PageFactory $resultPageFactory,
        SliderFactory $sliderFactory,
        Registry $registry,
        Context $context
    ) {
        $this->resultPageFactory = $resultPageFactory;

        parent::__construct($sliderFactory, $registry, $context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|ResponseInterface|Redirect|ResultInterface|Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('slider_id');
        /** @var \Zou\BannerSlider\Model\Slider $slider */
        $slider = $this->initSlider();

        if ($id) {
            $slider->load($id);
            if (!$slider->getId()) {
                $this->messageManager->addError(__('This Slider no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath(
                    'mpbannerslider/*/edit',
                    [
                        'slider_id' => $slider->getId(),
                        '_current'  => true
                    ]
                );

                return $resultRedirect;
            }
        }

        $data = $this->_session->getData('mpbannerslider_slider_data', true);
        if (!empty($data)) {
            $slider->setData($data);
        }

        /** @var \Magento\Backend\Model\View\Result\Page|Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Zou_BannerSlider::slider');
        $resultPage->getConfig()->getTitle()
            ->set(__('Sliders'))
            ->prepend($slider->getId() ? $slider->getName() : __('New Slider'));

        return $resultPage;
    }
}
