<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Haosuo\Wechat\Model;

use Haosuo\Wechat\Model\Method\AbstractMethod;
use Magento\Quote\Api\Data\PaymentMethodExtensionInterface;

/**
 * Class Wechat
 *
 * @method PaymentMethodExtensionInterface getExtensionAttributes()
 *
 * @api
 * @since 100.0.2
 */
class Wechat extends AbstractMethod
{
    const PAYMENT_METHOD_CHECKMO_CODE = 'wechat';

    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_CHECKMO_CODE;

    /**
     * @var string
     */
    protected $_formBlockType = \Haosuo\Wechat\Block\Form\Wechat::class;

    /**
     * @var string
     */
    protected $_infoBlockType = \Haosuo\Wechat\Block\Info\Wechat::class;

    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = true;



}
