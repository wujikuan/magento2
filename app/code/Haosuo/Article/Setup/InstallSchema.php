<?php
/**
 * Copyright © 2023 Haosuo. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Haosuo\Article\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;


/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
       $this->createPhysicalStores($setup);
    }
    protected function createPhysicalStores(SchemaSetupInterface $setup)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'quote_donations'
         */
        $table = $installer->getConnection()->newTable($installer->getTable('custom_article'))
        ->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'ID'
            )
        ->addColumn(
            'title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'Title'
            )
        ->addColumn(
                'image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '255',
                [],
                'image'
            )
        ->addColumn(
            'description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '1k',
            [],
            'description'
            )
        ->addColumn(
            'content',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '10k',
            [],
            'Content'
            )
        ->addColumn(
            'website',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            [],
            'website'
            )
        ->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => true,'default' => '0'],
            'status'
            )
        ->addColumn(
                'sort_order',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                '5',
                ['default'=>'0'],
                'sort_order'
            )
        ;
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
