<?php
namespace Learning\HelloPage\Controller\Page;

// use Magento\Backend\App\Action\Content;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\Result\JsonFactory;

class View extends Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    public function __construct(Context $context, JsonFactory $resultJsonFactory)
    {
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    public function execute(){
        $result = $this->resultJsonFactory->create();
        $data=['message'=>'Hello World'];
        return $result->setData($data);
    }
}
