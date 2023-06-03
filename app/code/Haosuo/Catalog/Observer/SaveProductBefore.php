<?php

namespace Haosuo\Catalog\Observer;

use Magento\Framework\Event\Observer;

class SaveProductBefore implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $product = $observer->getProduct();
        $select_choose = $product->getData('select_choose');
        if (!in_array($select_choose,['a','b','c'])){
            throw new \Magento\Framework\Exception\LocalizedException(__('select error'));
        }
    }
}
