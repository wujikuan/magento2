<?php

namespace CustomCheckout\Payment\Model;

class MyCustomPaymentConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getConfig()
    {
        return [
             'key' => 'value', // pairs of configuration
            'appId'=>'2023000001',
            'publicKey'=>'1222222222222222223ddddddseeeee',
            'privateKey'=>'33333333333fddddddddddfds'
        ];
    }
}
