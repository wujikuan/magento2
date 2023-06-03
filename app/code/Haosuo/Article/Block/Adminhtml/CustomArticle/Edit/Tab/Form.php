<?php
namespace Haosuo\Article\Block\Adminhtml\CustomArticle\Edit\Tab;

use Haosuo\Article\Model\Status;

class Form extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {

    const FIELD_NAME_SUFFIX = 'customArticle';

    protected $_fieldFactory;
    protected $_dataHelper;
    protected $_systemStore;
    protected $_scopeConfig;
    protected $_wysiwygConfig;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Haosuo\Article\Helper\Data $dataHelper
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory $fieldFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Haosuo\Article\Helper\Data $dataHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory $fieldFactory,
        \Magento\Store\Model\System\Store $systemStore,
         \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    ) {
        $this->_dataHelper = $dataHelper;
        $this->_systemStore = $systemStore;
        $this->_fieldFactory = $fieldFactory;
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareLayout() {
        $this->getLayout()->getBlock('page.title')->setPageTitle($this->getPageTitle());
    }

    protected function _prepareForm() {
        $physicalStore = $this->getCustomArticle();
        $isElementDisabled = true;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        /*
         * declare dependence
         */
        // dependence block
        $dependenceBlock = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Form\Element\Dependence'
        );

        // dependence field map array
        $fieldMaps = [];

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('CustomArticle Information')]);
        if ($physicalStore->getId()) {
            $fieldset->addField(
                'id',
                'hidden',
                [
                    'name' => 'id'
                ]
            );
        }

        $fieldset->addField(
            'title',
            'text',
            [
            'name' => 'title',
            'label' => __('Title'),
            'title' => __('Title'),
            'required' => true,
            'class' => 'required-entry',
            ]
        );
         $fieldset->addField(
         'image',
         'image',
             [
                 'name' => 'image',
                 'label' => __('Image'),
                 'title' => __('Image'),
                 'required' => true,
                 'class' => 'required-entry',
                 'note' => 'Allow image type: jpg, jpeg, gif, png',
             ]
         );


        $fieldset->addField(
            'description', 'editor', [
            'name' => 'description',
            'label' => __('Description'),
            'title' => __('Description'),
            'class' => 'required-entry',
            'wysiwyg' => true,
            'required' => false,
            'config' => $this->_wysiwygConfig->getConfig(),
            ]
        );

        $fieldset->addField(
            'content', 'editor', [
                'name' => 'Content',
                'label' => __('Content'),
                'title' => __('Content'),
                'class' => 'required-entry',
                'wysiwyg' => true,
                'required' => false,
                'config' => $this->_wysiwygConfig->getConfig(),
            ]
        );

        $fieldset->addField(
            'website', 'text', [
                'name' => 'website',
                'label' => __('Website URL'),
                'title' => __('Website URL')
            ]
        );


        $fieldset->addField(
            'status', 'select', [
            'name' => 'status',
            'label' => __('Status'),
            'title' => __('Status'),
            'required' => true,
            'values' => Status::getAvailableStatuses(),
            'class' => 'required-entry'
            ]
        );

        $fieldset->addField(
            'sort_order',
            'number', [
                'name' => 'sort_order',
                'label' => __('Sort Order'),
                'title' => __('Sort Order')
            ]
        );

        $form->setValues($physicalStore->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getWebsites() {
        $stores = array();
        $store = $this->_dataHelper->getAllStores();
        foreach ($store as $k => $v) {
            $stores[$k]['value'] = $v->getWebsiteId();
            $stores[$k]['label'] = $v->getName();
        }
        array_unshift($stores, ['value' => '', 'label' => __('All Store Views')]);
        return $stores;
    }

    public function getCustomArticle() {
        return $this->_coreRegistry->registry('customArticle');
    }

    public function getPageTitle() {
        return $this->getCustomArticle()->getId() ?
            __("Edit CustomArticle '%1'", $this->escapeHtml($this->getCustomArticle()->getName())) : __('New CustomArticle');
    }

    /**
     * Prepare label for tab.
     *
     * @return string
     */
    public function getTabLabel() {
        return __('CustomArticle Information');
    }

    /**
     * Prepare title for tab.
     *
     * @return string
     */
    public function getTabTitle() {
        return __('CustomArticle Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab() {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden() {
        return false;
    }

}
