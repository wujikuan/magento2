<?php

namespace Haosuo\Alipay\Controller\Alipay;

use Haosuo\Alipay\Model\AlipayTrade\AlipayConfig;
use Haosuo\Alipay\Model\AlipayTrade\pagepay\service\AlipayTradeService;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
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

    /**
     * @var InvoiceService
     */
    private $invoiceService;

    /**
     * @var SalesData
     */
    private $salesData;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Registry $registry,
        InvoiceSender $invoiceSender,
        ShipmentSender $shipmentSender,
        ShipmentFactory $shipmentFactory,
        InvoiceService $invoiceService,
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

        $config = AlipayConfig::getConfig();
        $alipayService = new AlipayTradeService($config);
        // 测试数据
       /* $_POST = array (
            'gmt_create' => '2023-05-11 16:45:40',
            'charset' => 'UTF-8',
            'gmt_payment' => '2023-05-11 16:45:52',
            'notify_time' => '2023-05-11 16:45:55',
            'subject' => 'Order 000000018',
            'sign' => 'PB+/jFLvgMTkXgv2OoOH7ZdcC5UCU2FDm17vbUKCQlNyocGCFBYKC8dFY+q+7KIKfLGBRKGAZ7bU3dBwQ3TOqhKEHg3VmgU5f8+SyR3FfHivF9ml1uHmN9JE+GY6XRazwrc/DFy57fRmxiGjSw7bmhhdLsFh2zuCK4WWZGiPRKR1RIXRYpaSvNPkRp5Agcc1u30aiSVMh9lfCU5JIQxzQhgpluBkxqvfRA38Mku1KR5Qi+plMQKCF5SH6JAzIkFZHcWDGG8WVfYoLDsdKnooVf0OI3sW/ZYbrs2EPUKJDGEDKrIwHN7gcNXs6AFpsyHffya408OYTPaywIz4206pDQ==',
            'buyer_id' => '2088102177292779',
            'body' => 'Order 000000018',
            'invoice_amount' => '78.00',
            'version' => '1.0',
            'notify_id' => '2023051100222164607092770522089415',
            'fund_bill_list' => '[{"amount":"78.00","fundChannel":"ALIPAYACCOUNT"}]',
            'notify_type' => 'trade_status_sync',
            'out_trade_no' => '000000018',
            'total_amount' => '78.00',
            'trade_status' => 'TRADE_SUCCESS',
            'trade_no' => '2023051122001492770502036725',
            'auth_app_id' => '2016092400585786',
            'receipt_amount' => '78.00',
            'point_amount' => '0.00',
            'app_id' => '2016092400585786',
            'buyer_pay_amount' => '78.00',
            'sign_type' => 'RSA2',
            'seller_id' => '2088102177065553',
        );*/

        $arr=$_POST;

        $alipayService->writeLog('alipay_result_start');
        $alipayService->writeLog(var_export($_POST,true));
        $alipayService->writeLog('alipay_result_end');
        // 验签
        $result = true;
        $result = $alipayService->check($arr);

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
                $this->updateOrderAndAddInvoice($arr['out_trade_no'],$alipayService);
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

    private function updateOrderAndAddInvoice($incrementId,$alipayService){

        try {
            $order = $this->_objectManager->create(\Magento\Sales\Model\Order::class)->load($incrementId,'increment_id');
            if (!$order->getId()) {
                // throw new \Magento\Framework\Exception\LocalizedException(__('The order no longer exists.'));
                $alipayService->writeLog('The order no longer exists');
                return 'fail';
            }

            // 如果订单可以创建发票信息
            if ($order->canInvoice()) {
                // 组装发票信息
                $invoiceItems= [];
                /*$orderItemsCollection = $order->getItemsCollection();
                if (!empty($orderItemsCollection->getItems())){
                    foreach ($orderItemsCollection->getItems() as $item){
                        if ($item->getParentItem()){
                            continue;
                        }
                        $itemData = $item->getData();
                        $invoiceItems[$item->getItemId()] = $itemData['product_options']['info_buyRequest']['qty'];
                    }
                }*/
                $invoice = $this->invoiceService->prepareInvoice($order, $invoiceItems);
                if (!$invoice) {
                    // throw new LocalizedException(__("The invoice can't be saved at this time. Please try again later."));
                    $alipayService->writeLog("The invoice can't be saved at this time. Please try again later");
                    return 'fail';
                }

                if (!$invoice->getTotalQty()) {
//                    throw new \Magento\Framework\Exception\LocalizedException(
//                        __("The invoice can't be created without products. Add products and try again.")
//                    );
                    $alipayService->writeLog("The invoice can't be created without products. Add products and try again");
                    return 'fail';
                }

                $this->registry->register('current_invoice', $invoice);
                if (!empty($data['capture_case'])) {
                    $invoice->setRequestedCaptureCase($data['capture_case']);
                }

                $invoice->register();
                $invoice->getOrder()->setCustomerNoteNotify(!empty($data['send_email']));
                $invoice->getOrder()->setIsInProcess(true);

                $transactionSave = $this->_objectManager->create(
                    \Magento\Framework\DB\Transaction::class
                )->addObject(
                    $invoice
                )->addObject(
                    $invoice->getOrder()
                );
                $transactionSave->save();
            }
        }catch (LocalizedException $e){
            $alipayService->writeLog("The invoice can't be created without products. Add products and try again");
            return 'fail';
        }catch (\Exception $e) {
            $alipayService->writeLog("The invoice can't be saved at this time. Please try again later.");
            return 'fail';
        }
        return 'success';
    }
}