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

    protected $_alipayTradeRefund;
    protected $_order;
    protected $_alipayTradeService;

    public function __construct(
        \Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        DirectoryHelper $directory = null,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $paymentData, $scopeConfig, $logger, $resource, $resourceCollection, $data, $directory);

    }

    /**
     * Refund specified amount for payment
     *
     * @param \Magento\Framework\DataObject|InfoInterface $payment
     * @param float $amount
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @deprecated 100.2.0
     */
    public function refund(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        if (!$this->canRefund()) {
            throw new \Magento\Framework\Exception\LocalizedException(__('The refund action is not available.'));
        }
        $orderId = $payment->getData('parent_id');
        $order = $this->_order->create()->load($orderId);
        if (!$order){
            throw new \Magento\Framework\Exception\LocalizedException(__('The refund order is not exist.'));
        }
        $outTradeNo = $order->getData('increment_id');

        $tradeNo = $payment->getData('last_trans_id');

        $outTradeNo= '2023518104026278';
        $tradeNo = '2023051822001492770502039592';
        $this->_alipayTradeRefund->setOutTradeNo($outTradeNo);
        $this->_alipayTradeRefund->setTradeNo($tradeNo);
        $this->_alipayTradeRefund->setRefundAmount($amount);
        $response = $this->alipayTradeService->Refund($this->alipayTradeRefund);

        $responseResult = json_decode($response);
        if ($responseResult['code'] != 10000 && $response['msg'] != 'Success'){
            throw new \Magento\Framework\Exception\LocalizedException(__('The refund order is fail.'));
        }
        $payment->setIsTransactionClosed(true);
        $payment->save();

        return $this;
    }
}
