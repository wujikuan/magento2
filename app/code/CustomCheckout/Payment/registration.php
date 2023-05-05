<?php
   use  \Magento\Framework\Component\ComponentRegistrar;
    ComponentRegistrar::register(
        ComponentRegistrar::MODULE,
        'CustomCheckout_Payment',
        __DIR__
    );
