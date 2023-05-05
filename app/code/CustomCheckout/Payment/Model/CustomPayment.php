<?php

namespace CustomCheckout\Payment\Model;

class CustomPayment extends \Magento\Payment\Model\Method\AbstractMethod
{
    protected $_code = 'custompayment';
    protected $_isOffline = true;

    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        return parent::isAvailable($quote) && $quote->getGrandTotal() > 0;
    }

    public function getTitle()
    {
        return __('Custom Alipay Payment');
    }

    public function canUseForCurrency($currencyCode)
    {
        return true;
    }

    public function getInstructions()
    {
        return __('Please send payment to the following address.');
    }

}
