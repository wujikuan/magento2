<?php

namespace Haosuo\Catalog\Ui\DataProvider\Product\Form\Modifier;

use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Customer\Model\Customer\Source\GroupSourceInterface;
use Magento\Directory\Helper\Data;
use Magento\Framework\App\ObjectManager;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form\Element\DataType\Number;
use Magento\Ui\Component\Form\Element\DataType\Price;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Modal;
use Magento\Framework\Stdlib\ArrayManager;


class SelectChooseSecond extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier
{
    protected $meta = [];

    /**
     * @inheritDoc
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function modifyMeta(array $meta)
    {
        $meta['select_choose_second'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Select Choose Second'),
                        'sortOrder' => 50,
                        'collapsible' => true,
                        'componentType' => Fieldset::NAME
                    ]
                ]
            ],
            'children' => [
                'select_choose_second' => [
                    'arguments' => [
                        'data' => [
                            'options' => [
                                [
                                    'value'=>'a',
                                    'label'=>__('A')
                                ],
                                [
                                    'value'=>'b',
                                    'label'=>__('B')
                                ],
                                [
                                    'value'=>'c',
                                    'label'=>__('C')
                                ]
                            ],
                            'config' => [
                                'formElement' => 'select',
                                'componentType' => Field::NAME,
                                'visible' => 1,
                                'required' => 1,
                                'label' => __('Select Choose Second')
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $this->meta = $meta;
        return $this->meta;
    }
}
