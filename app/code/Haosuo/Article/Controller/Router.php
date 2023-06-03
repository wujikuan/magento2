<?php
namespace Haosuo\Article\Controller;

use Magento\Framework\App\RouterInterface;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Url;

class Router implements RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;

    /**
     * Event manager
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    /**
     * Response
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $response;

    /**
     * @var bool
     */
    protected $dispatched;

    protected $_brandCollection;

    protected $_groupCollection;

    /**
     * Store manager
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    protected $_physicalStoreStaff;
    protected $_physicalStore;
    /**
     * @param ActionFactory
     * @param ResponseInterface
     * @param ManagerInterface
     * @param StoreManagerInterface
     * @param \Ves\Blog\Helper\Data
     * @param \Magento\Framework\Registry
     */
    public function __construct(
        ActionFactory $actionFactory,
        ResponseInterface $response,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        \Haosuo\Article\Helper\Data $physicalStoresHelper,
        \Haosuo\Article\Model\CustomArticle $physicalStore,
        \Magento\Framework\Registry $registry,
    )
    {
        $this->actionFactory = $actionFactory;
        $this->eventManager  = $eventManager;
        $this->response      = $response;
        $this->storeManager  = $storeManager;
        $this->_physicalStoresHelper   = $physicalStoresHelper;
        $this->_coreRegistry = $registry;
        $this->_physicalStore = $physicalStore;
    }

    /**
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface
     */
    public function match(RequestInterface $request)
    {
        $store = $this->storeManager->getStore();
        if (!$this->dispatched) {
            $urlKey = trim($request->getPathInfo(), '/');

            $params = $request->getParams();
            $origUrlKey = $urlKey;
            /** @var Object $condition */
            $condition = new DataObject(['url_key' => $urlKey, 'continue' => true]);
            $this->eventManager->dispatch(
                'haosuo_article_controller_router_match_before',
                [
                    'router' => $this,
                    'condition' => $condition
                ]
            );
            $urlKey = $condition->getUrlKey();
            if ($condition->getRedirectUrl()) {
                $this->response->setRedirect($condition->getRedirectUrl());
                $request->setDispatched(true);
                return $this->actionFactory->create(
                    'Magento\Framework\App\Action\Redirect',
                    ['request' => $request]
                );
            }
            if (!$condition->getContinue()) {
                return null;
            }
            $urlKeys = explode("/", $urlKey);
            $urlKeysOrgin = $urlKeys;
            // stores
            //var_dump($urlKeysOrgin);exit;
            if(count($urlKeys)==2 && $urlKeys[0] == 'customArticle'){
                $alias = $urlKeys[1];
                $pyStore = $this->_physicalStore->getCollection()->addFieldToFilter("url_key", $alias)
                    ->getFirstItem();
                if(!empty($pyStore->getData())){
                    $this->_coreRegistry->register("current_customAticle", $pyStore);
                    $request->setModuleName('article')
                        ->setControllerName('customArticle')
                        ->setActionName('view');
                    $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $origUrlKey);
                    $request->setDispatched(true);
                    $this->dispatched = true;
                    return $this->actionFactory->create(
                        'Magento\Framework\App\Action\Forward',
                        ['request' => $request]
                    );
                }
            }
        }
    }
}
