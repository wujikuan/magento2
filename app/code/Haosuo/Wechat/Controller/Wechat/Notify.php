<?php

namespace Haosuo\Wechat\Controller\Wechat;

use Haosuo\Wechat\Helper\Data;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Sales\Helper\Data as SalesData;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Sales\Model\Order\Email\Sender\ShipmentSender;
use Magento\Sales\Model\Order\ShipmentFactory;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Sales\Model\Order\CreditmemoFactory;
use Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface as TransactionBuilderInterface;
use WeChatPay\Crypto\Rsa;
use WeChatPay\Crypto\AesGcm;
use WeChatPay\Formatter;
use Haosuo\Wechat\Model\WechatPayMethod;


class Notify extends \Magento\Framework\App\Action\Action implements CsrfAwareActionInterface
{

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;


    /**
     * @var InvoiceSender
     */
    protected $invoiceSender;

    /**
     * @var ShipmentSender
     */
    protected $shipmentSender;

    /**
     * @var ShipmentFactory
     */
    protected $shipmentFactory;

    /**
     * @var Registry
     */
    protected $registry;

    protected $_dataHelper;
    protected $_order;

    /**
     * @var InvoiceService
     */
    private $invoiceService;

    /**
     * @var SalesData
     */
    private $salesData;

    protected $_creditmemo ;
    protected $_transactionBuilder;
    protected $_wechatPayMethod;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Registry $registry
     * @param InvoiceSender $invoiceSender
     * @param ShipmentSender $shipmentSender
     * @param ShipmentFactory $shipmentFactory
     * @param InvoiceService $invoiceService
     * @param Data $dataHelper
     * @param \Magento\Sales\Model\OrderFactory $order
     * @param CreditmemoFactory $creditmemoFactory
     * @param TransactionBuilderInterface $transactionBuilder
     * @param Rsa $res
     * @param AesGcm $aesGcm
     * @param Formatter $formatter
     * @param WechatPayMethod $wechatPayMethod
     * @param SalesData|null $salesData
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Registry $registry,
        InvoiceSender $invoiceSender,
        ShipmentSender $shipmentSender,
        ShipmentFactory $shipmentFactory,
        InvoiceService $invoiceService,
        Data $dataHelper,
        \Magento\Sales\Model\OrderFactory $order,
        CreditmemoFactory $creditmemoFactory,
        TransactionBuilderInterface $transactionBuilder,
        Rsa $res,
        AesGcm $aesGcm,
        Formatter $formatter,
        WechatPayMethod $wechatPayMethod,
        SalesData $salesData = null
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->registry = $registry;
        $this->invoiceSender = $invoiceSender;
        $this->shipmentSender = $shipmentSender;
        $this->shipmentFactory = $shipmentFactory;
        $this->invoiceService = $invoiceService;
        parent::__construct($context);
        $this->salesData = $salesData ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(SalesData::class);
        $this->_dataHelper = $dataHelper;
        $this->_order = $order;
        $this->_creditmemo = $creditmemoFactory;
        $this->_transactionBuilder = $transactionBuilder;
        $this->_wechatPayMethod = $wechatPayMethod;

    }

    public function createCsrfValidationException(
        RequestInterface $request
    ): ?InvalidRequestException {
        return null;
    }
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        // 直接返回通过
        return true;
    }

    /**
     * @return array|string[]|null
     */
    public function execute()
    {
        //获取返回json数据
        $getCallBackJson = file_get_contents('php://input');
        // 测试数据
        $getCallBackJson = '{
            "transaction_id":"1217752501201407033233368018",
            "amount":{
                "payer_total":100,
                "total":100,
                "currency":"CNY",
                "payer_currency":"CNY"
            },
            "mchid":"1230000109",
            "trade_state":"SUCCESS",
            "bank_type":"CMC",
            "promotion_detail":[
                {
                    "amount":100,
                    "wechatpay_contribute":0,
                    "coupon_id":"109519",
                    "scope":"GLOBAL",
                    "merchant_contribute":0,
                    "name":"单品惠-6",
                    "other_contribute":0,
                    "currency":"CNY",
                    "stock_id":"931386",
                    "goods_detail":[
                        {
                            "goods_remark":"商品备注信息",
                            "quantity":1,
                            "discount_amount":1,
                            "goods_id":"M1006",
                            "unit_price":100
                        },
                        {
                            "goods_remark":"商品备注信息",
                            "quantity":1,
                            "discount_amount":1,
                            "goods_id":"M1006",
                            "unit_price":100
                        }
                    ]
                },
                {
                    "amount":100,
                    "wechatpay_contribute":0,
                    "coupon_id":"109519",
                    "scope":"GLOBAL",
                    "merchant_contribute":0,
                    "name":"单品惠-6",
                    "other_contribute":0,
                    "currency":"CNY",
                    "stock_id":"931386",
                    "goods_detail":[
                        {
                            "goods_remark":"商品备注信息",
                            "quantity":1,
                            "discount_amount":1,
                            "goods_id":"M1006",
                            "unit_price":100
                        },
                        {
                            "goods_remark":"商品备注信息",
                            "quantity":1,
                            "discount_amount":1,
                            "goods_id":"M1006",
                            "unit_price":100
                        }
                    ]
                }
            ],
            "success_time":"2018-06-08T10:34:56+08:00",
            "payer":{
                "openid":"oUpF8uMuAJO_M2pxb1Q9zNjWeS6o"
            },
            "out_trade_no":"1217752501201407033233368018",
            "appid":"wxd678efh567hg6787",
            "trade_state_desc":"支付成功",
            "trade_type":"MICROPAY",
            "attach":"自定义数据",
            "scene_info":{
                "device_id":"013467007045764"
            }
        }';

        //转化为关联数组
        $getCallBackArray = json_decode($getCallBackJson, true);
        // todo delete start
        $resultArray = $getCallBackArray;
        $resultArray['out_trade_no'] = $_POST['increamentId'];
        // todo delete end

        /*$this->_dataHelper ->writeLog('wechat_start_notify');
        $this->_dataHelper ->writeLog(var_export($getCallBackArray,true));
        $this->_dataHelper ->writeLog('wechat_end_notify');
        //获取需要解密字段
        $associatedData = $getCallBackArray['resource']['associated_data'];
        $nonceStr = $getCallBackArray['resource']['nonce'];
        $ciphertext = $getCallBackArray['resource']['ciphertext'];
        $resultJson = $this->_wechatPayMethod->decryptToString($associatedData, $nonceStr, $ciphertext);
        //解密结果，为关联数组格式
        $resultArray = json_decode($resultJson, true);*/

        $this->_dataHelper ->writeLog('wechat_start_decrypt');
        $this->_dataHelper ->writeLog(var_export($getCallBackArray,true));
        $this->_dataHelper ->writeLog('wechat_end_decrypt');
        //交易成功
        if ($resultArray['trade_state'] === 'SUCCESS') {

            //这里填写交易成功的相关业务，如更新账单状态，其中可能需要用到的参数如下
            //$resultArray['out_trade_no']       商户订单号
            //$resultArray['transaction_id']     订单号
            //$resultArray['amount']['total']    订单金额
            $res = $this->updateOrderAndAddInvoice($resultArray['out_trade_no'],$resultArray);
            echo json_encode($res);
        }else{
            echo json_encode(['code' => 'FAIL', 'message' => '失败']);
        }
    }

    /**
     * @param $incrementId
     * @param $data
     * @return string[]|array|void
     */
    private function updateOrderAndAddInvoice($incrementId,$data){

        try {
            $order = $this->_order->create()->loadByIncrementId($incrementId);

            if (!$order->getId()) {
                $this->_dataHelper->writeLog('The order no longer exists');
                return ['code' => 'FAIL', 'message' => '失败'];
            }

            // 如果订单可以创建发票信息
            if ($order->canInvoice()) {
                // 组装发票信息
                $invoiceItems= [];
                $invoice = $this->invoiceService->prepareInvoice($order, $invoiceItems);
                if (!$invoice) {
                    $this->_dataHelper->writeLog("The invoice can't be saved at this time. Please try again later");
                    return ['code' => 'FAIL', 'message' => '失败'];
                }

                if (!$invoice->getTotalQty()) {
                    $this->_dataHelper->writeLog("The invoice can't be created without products. Add products and try again");
                    return ['code' => 'FAIL', 'message' => '失败'];
                }

                $this->registry->register('current_invoice', $invoice);
                // capture_case ['online','offline','not_capture']
                $invoice->setRequestedCaptureCase('online');

                $invoice->register();
                $invoice->getOrder()->setCustomerNoteNotify(!empty($data['send_email']));
                $invoice->setData('transaction_id',$data['transaction_id']);
                $invoice->getOrder()->setIsInProcess(true);

                $transactionSave = $this->_objectManager->create(
                    \Magento\Framework\DB\Transaction::class
                )->addObject(
                    $invoice
                )->addObject(
                    $invoice->getOrder()
                );

                $payment = $order->getPayment();
                $transaction = $this->_transactionBuilder
                    ->setPayment($payment)
                    ->setOrder($order)
                    ->setTransactionId($data['transaction_id'])
                    ->addAdditionalInformation(
                        \Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS,
                        $data
                    )
                    ->setFailSafe(true)
                    ->build(\Magento\Sales\Model\Order\Payment\Transaction::TYPE_CAPTURE);

                $payment->setIsTransactionClosed(false);
                $payment->addTransactionCommentsToOrder($data['transaction_id'],' Payment Method Wechat Success');
                $payment->save();
                $order->save();
                $transactionSave->save();
            }

            return ['code' => 'SUCCESS', 'message' => ''];
        }catch (LocalizedException $e){
            $this->_dataHelper->writeLog("The invoice can't be created without products. Add products and try again");
            return ['code' => 'FAIL', 'message' => '失败'];
        }catch (\Exception $e) {
            $this->_dataHelper->writeLog("The invoice can't be saved at this time. Please try again later.");
            return ['code' => 'FAIL', 'message' => '失败'];
        }

        return ['code' => 'FAIL', 'message' => '失败'];
    }




}
