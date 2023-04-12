<?php
namespace Zou\Demo\Block\Adminhtml\PhysicalStoreStaff;

use Zou\Demo\Model\Status;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * physicalStore collection factory.
     *
     * @var \Zou\Demo\Model\ResourceModel\PhysicalStore\CollectionFactory
     */
    protected $_physicalStoreCollectionFactory;
    protected $_physicalStoreStaffCollectionFactory;
    /**
     * helper.
     *
     * @var \Zou\Demo\Helper\Data
     */
    protected $_physicalStoresHelper;

    /**
     * [__construct description].
     *
     * @param \Magento\Backend\Block\Template\Context                         $context
     * @param \Magento\Backend\Helper\Data                                    $backendHelper
     * @param \Zou\Demo\Model\ResourceModel\PhysicalStore\CollectionFactory $physicalStoreCollectionFactory
     * @param \Zou\Demo\Helper\Data                             $physicalStoreStaffHelper
     * @param array                                                           $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Zou\Demo\Model\ResourceModel\PhysicalStoreStaff\CollectionFactory $physicalStoreStaffCollectionFactory,
        \Zou\Demo\Model\ResourceModel\PhysicalStore\CollectionFactory $physicalStoreCollectionFactory,
        \Zou\Demo\Helper\Data $physicalStoresHelper,
        array $data = []
    ) {
        $this->_physicalStoreCollectionFactory = $physicalStoreCollectionFactory;
        $this->_physicalStoreStaffCollectionFactory = $physicalStoreStaffCollectionFactory;
        $this->_physicalStoresHelper = $physicalStoresHelper;
        //echo $collection->getSelect();exit;
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
        $this->setId('physicalStoreStaffGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
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
        $collection = $this->_physicalStoreStaffCollectionFactory->create();
        $this->setCollection($collection);
        //echo $collection->getSelect();exit;
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
            'firstname',
            [
                'header' => __('First Name'),
                'index' => 'firstname',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'lastname',
            [
                'header' => __('Last Name'),
                'index' => 'lastname',
                'class' => 'xxx',
                'width' => '50px',
            ]
            );
        $this->addColumn(
            'email',
            [
                'header' => __('Email'),
                'index' => 'email',
                'class' => 'xxx',
                'width' => '50px',
            ]
            );
        $this->addColumn(
            'phone',
            [
                'header' => __('Phone'),
                'index' => 'phone',
                'class' => 'xxx',
                'width' => '50px',
            ]
            ); 
        $this->addColumn(
            'sort_order',
            [
                'header' => __('Sort Order'),
                'index' => 'sort_order',
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
            'store_id',
            [
                'header' => __('Store'),
                'index'   => 'store_id',
                'type'    => 'options',
                'options' => $this->getPhysicalStoreAvailableOption(),
                'class' => 'xxx',
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
                'index' => 'stores',
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
        $this->getMassactionBlock()->setFormFieldName('physicalStoreStaff');

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
    public function getPhysicalStoreAvailableOption()
    {
        $option = [];
        $physicalStoreCollection = $this->_physicalStoreCollectionFactory->create()->addFieldToSelect(['name']);
    
        foreach ($physicalStoreCollection as $physicalStore) {
            $option[$physicalStore->getId()] = $physicalStore->getName();
        }
    
        return $option;
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
