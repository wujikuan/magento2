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

namespace Zou\BannerSlider\Observer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Layout;
use Zou\BannerSlider\Block\Slider;
use Zou\BannerSlider\Helper\Data;
use Zou\BannerSlider\Model\Config\Source\Location;

/**
 * Class AddBlock
 * @package Zou\AutoRelated\Observer
 */
class AddBlock implements ObserverInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * AddBlock constructor.
     *
     * @param RequestInterface $request
     * @param Data $helperData
     */
    public function __construct(
        RequestInterface $request,
        Data $helperData
    ) {
        $this->request    = $request;
        $this->helperData = $helperData;
    }

    /**
     * @param Observer $observer
     *
     * @return $this
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        if (!$this->helperData->isEnabled()) {
            return $this;
        }

        $type = array_search($observer->getEvent()->getElementName(), [
            'header'           => 'header',
            'content'          => 'content',
            'page-top'         => 'main.content',
            'footer-container' => 'footer-container',
            'sidebar'          => 'catalog.leftnav'
        ], true);

        if ($type !== false) {
            /** @var Layout $layout */
            $layout         = $observer->getEvent()->getLayout();
            $fullActionName = $this->request->getFullActionName();
            $output         = $observer->getTransport()->getOutput();

            foreach ($this->helperData->getActiveSliders() as $slider) {
                $locations = array_filter(explode(',', $slider->getLocation()));
                foreach ($locations as $value) {
                    if ($value === Location::USING_SNIPPET_CODE) {
                        continue;
                    }
                    [$pageType, $location] = explode('.', $value);
                    if (($fullActionName === $pageType || $pageType === 'allpage') &&
                        strpos($location, $type) !== false
                    ) {
                        $content = $layout->createBlock(Slider::class)
                            ->setSlider($slider)
                            ->toHtml();

                        if (strpos($location, 'top') !== false) {
                            $output = "<div id=\"zou-bannerslider-block-before-{$type}-{$slider->getId()}\">
                                        $content</div>" . $output;
                        } else {
                            $output .= "<div id=\"zou-bannerslider-block-after-{$type}-{$slider->getId()}\">
                                        $content</div>";
                        }
                    }
                }
            }

            $observer->getTransport()->setOutput($output);
        }

        return $this;
    }
}
