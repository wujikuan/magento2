<?php

namespace Haosuo\Alipay\Controller\Alipay;

use Haosuo\Alipay\Helper\Data;
use Haosuo\Alipay\Model\AlipayService;
use Haosuo\Alipay\Model\AlipayTrade\pagepay\service\AlipayTradeService;
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

    protected $alipayService;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Registry $registry,
        InvoiceSender $invoiceSender,
        ShipmentSender $shipmentSender,
        ShipmentFactory $shipmentFactory,
        InvoiceService $invoiceService,
        SalesData $salesData = null,
        Data $dataHelper,
        \Magento\Sales\Model\OrderFactory $order,
        AlipayTradeService $alipayTradeService,
        CreditmemoFactory $creditmemoFactory,
        TransactionBuilderInterface $transactionBuilder,
        AlipayService $alipayService
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
        $this->_alipayTradeService = $alipayTradeService;
        $this->_creditmemo = $creditmemoFactory;
        $this->_transactionBuilder = $transactionBuilder;
        $this->alipayService = $alipayService;

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

    public function execute(){

        $arr=$_POST;

        $this->_dataHelper->writeLog('alipay_result_start');
        $this->_dataHelper->writeLog(var_export($_POST,true));
        $this->_dataHelper->writeLog('alipay_result_end');

        // 验签
        $result = $this->_alipayTradeService->check($arr);

        if($result) {//验证成功
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            //交易状态
            $trade_status = $_POST['trade_status'];

            if($_POST['trade_status'] == 'TRADE_FINISHED') {
                // TODO
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序

                //注意：
                // 退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {

                $this->updateOrderAndAddInvoice($arr['out_trade_no'],$arr);
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知

            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success";	//请不要修改或删除
        }else {
            //验证失败
            echo "fail";
        }
    }

    private function updateOrderAndAddInvoice($incrementId,$data){

        try {
            $order = $this->_order->create()->loadByIncrementId($incrementId);

            if (!$order->getId()) {
                $this->_dataHelper->writeLog('The order no longer exists');
                return 'fail';
            }

            // 如果订单可以创建发票信息
            if ($order->canInvoice()) {
                // 组装发票信息
                $invoiceItems= [];

                $invoice = $this->invoiceService->prepareInvoice($order, $invoiceItems);
                if (!$invoice) {
                    $this->_dataHelper->writeLog("The invoice can't be saved at this time. Please try again later");
                    return 'fail';
                }

                if (!$invoice->getTotalQty()) {
                    $this->_dataHelper->writeLog("The invoice can't be created without products. Add products and try again");
                    return 'fail';
                }

                $this->registry->register('current_invoice', $invoice);
                // capture_case ['online','offline','not_capture']
                $invoice->setRequestedCaptureCase('online');

                $invoice->register();
                $invoice->getOrder()->setCustomerNoteNotify(!empty($data['send_email']));
                $invoice->setData('transaction_id',$data['trade_no']);
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
                    ->setTransactionId($data['trade_no'])
                    ->addAdditionalInformation(
                        \Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS,
                        $data
                    )
                    ->setFailSafe(true)
                    ->build(\Magento\Sales\Model\Order\Payment\Transaction::TYPE_CAPTURE);

                $payment->setIsTransactionClosed(false);
                $payment->addTransactionCommentsToOrder($data['trade_no'],' Payment Method Alipay Success');

                $payment->save();
                $order->save();

                $transactionSave->save();
            }
            return 'success';
        }catch (LocalizedException $e){
            $this->_dataHelper->writeLog("The invoice can't be created without products. Add products and try again");
            return 'fail';
        }catch (\Exception $e) {
            $this->_dataHelper->writeLog("The invoice can't be saved at this time. Please try again later.");
            return 'fail';
        }
        return 'fail';
    }


}
