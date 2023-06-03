<?php

namespace Haosuo\Article\Block\Adminhtml\CustomArticle;

use Haosuo\Article\Model\Status;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    protected $_customArticleCollectionFactory;

    /**
     * helper.
     *
     */
    protected $_dataHelper;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Haosuo\Article\Model\ResourceModel\CustomArticle\CollectionFactory $customArticleCollectionFactory
     * @param \Haosuo\Article\Helper\Data $dataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Haosuo\Article\Model\ResourceModel\CustomArticle\CollectionFactory $customArticleCollectionFactory,
        \Haosuo\Article\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->_customArticleCollectionFactory = $customArticleCollectionFactory;
        $this->_dataHelper = $dataHelper;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * [_construct description].
     *
     * @return [type] [description]
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('customArticleGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection.
     *
     * @return [type] [description]
     */
    protected function _prepareCollection()
    {
        $collection = $this->_customArticleCollectionFactory->create();
       $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'title',
            [
                'header' => __('Title'),
                'index' => 'title',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );

        $this->addColumn(
            'description',
            [
                'header' => __('description'),
                'index' => 'description',
                'class' => 'xxx',
                'width' => '50px',
            ]
            );

        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'class' => 'xxx',
                'type' => 'options',
                'options' => Status::getAvailableStatuses(),
                'width' => '50px',
            ]
            );

        $this->addColumn(
            'edit',
            [
                'header' => __('Edit'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => '*/*/edit',
                        ],
                        'field' => 'id',
                    ],
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'id',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action',
            ]
        );
        $this->addExportType('*/*/exportCsv', __('CSV'));
        $this->addExportType('*/*/exportXml', __('XML'));
        $this->addExportType('*/*/exportExcel', __('Excel'));

        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('customArticle');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('*/*/massDelete'),
                'confirm' => __('Are you sure?'),
            ]
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    /**
     * get row url
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/*/edit',
            array('id' => $row->getId())
        );
    }
}
