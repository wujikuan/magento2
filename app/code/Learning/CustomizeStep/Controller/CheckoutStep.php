<?php
namespace CustomCheckout\Controller\Checkout;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Checkout\Model\Session;

class CustomStep extends Action
{
    protected $_resultPageFactory;
    protected $_checkoutSession;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Session $checkoutSession
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_checkoutSession = $checkoutSession;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->_checkoutSession->setCustomStep(true);
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Custom Checkout Step'));
        return $resultPage;
    }
}
