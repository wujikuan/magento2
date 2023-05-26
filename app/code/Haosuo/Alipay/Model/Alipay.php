<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Haosuo\Alipay\Model;

use Haosuo\Alipay\Helper\Data;
use Haosuo\Alipay\Model\AlipayTrade\pagepay\buildermodel\AlipayTradeRefundContentBuilder;
use Haosuo\Alipay\Model\AlipayTrade\pagepay\service\AlipayTradeService;
use Haosuo\Alipay\Model\Method\AbstractMethod;
use Haosuo\Alipay\Model\Method\RefundMethod;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Payment\Model\InfoInterface;
use Magento\Quote\Api\Data\PaymentMethodExtensionInterface;

/**
 * Class Alipay
 *
 * @method PaymentMethodExtensionInterface getExtensionAttributes()
 *
 * @api
 * @since 100.0.2
 */
class Alipay extends AbstractMethod
{
    const PAYMENT_METHOD_CHECKMO_CODE = 'alipay';

    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_CHECKMO_CODE;

    /**
     * @var string
     */
    protected $_formBlockType = \Haosuo\Alipay\Block\Form\Alipay::class;

    /**
     * @var string
     */
    protected $_infoBlockType = \Haosuo\Alipay\Block\Info\Alipay::class;

    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = true;



}
