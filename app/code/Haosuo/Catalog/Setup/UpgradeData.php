<?php
namespace Haosuo\Catalog\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Setup\EavSetupFactory;
/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    private $eavSetupFactory;

    /**
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        //         SchemaSetupInterface $schemaSetup
        ) {
            $this->eavSetupFactory = $eavSetupFactory;
    }
    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '0.1.3', '<')) {
            $attribuets = array(
                'select_choose' => array(
                    'type' => 'varchar',
                    'label' => 'Select Choose',
                    'input' => 'select',
                    'global'=>'content',
                    'source'=>\Haosuo\Catalog\Model\Config\Source\SelectChooseOptions::class,
                    'group'=>'content'
                ),
            );
            $this->createProductAttribute($setup, $attribuets);
        }
        $setup->endSetup();
    }

    function createProductAttribute($setup,$attribuets) {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $aScope = \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL;
        foreach ($attribuets as $code => $_attribute){
            if(isset($_attribute['type']) &&  $_attribute['type']
                && isset($_attribute['input']) &&  $_attribute['input']
                && isset($_attribute['label']) &&  $_attribute['label']
                ){
                    $input = $_attribute['input'];
                    if ($input == "boolean") {
                        $_attribute['source'] = "Magento\Eav\Model\Entity\Attribute\Source\Boolean";
                    }
                    $eavSetup->addAttribute(
                        \Magento\Catalog\Model\Product::ENTITY, $code, [
                            'group' => isset($_attribute['group'])?$_attribute['group']:'General',
                            'type' => $_attribute['type'],
                            'label' => $_attribute['label'],
                            'input' => $_attribute['input'],
                            'backend' => isset($_attribute['backend'])?$_attribute['backend']:null,
                            'required' => isset($_attribute['required'])?$_attribute['required']:false,
                            'sort_order' => isset($_attribute['sort_order'])?$_attribute['sort_order']:1000,
                            'user_defined' => isset($_attribute['user_defined'])?$_attribute['user_defined']:true,
                            'global' => isset($_attribute['global'])?$_attribute['global']:$aScope,
                            'used_in_product_listing' => true,
                            'visible' => true,
                            'source' => isset($_attribute['source'])?$_attribute['source']:'',
                            'wysiwyg_enabled'=>isset($_attribute['wysiwyg_enabled'])?$_attribute['wysiwyg_enabled']:false,
                            'default' => isset($_attribute['default'])?$_attribute['default']:null
                        ]
                    );
            }
        }
    }

}
