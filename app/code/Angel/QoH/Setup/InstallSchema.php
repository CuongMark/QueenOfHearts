<?php


namespace Angel\QoH\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $table_angel_qoh_ticket = $setup->getConnection()->newTable($setup->getTable('angel_qoh_ticket'));

        $table_angel_qoh_ticket->addColumn(
            'ticket_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
            'Entity ID'
        );

        $table_angel_qoh_ticket->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False,'unsigned' => true],
            'Product Id'
        );

        $table_angel_qoh_ticket->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False],
            'Customer Id'
        );

        $table_angel_qoh_ticket->addColumn(
            'start',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False],
            'Start Number'
        );

        $table_angel_qoh_ticket->addColumn(
            'end',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False],
            'End Number'
        );

        $table_angel_qoh_ticket->addColumn(
            'price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => False,'precision' => 12,'scale' => 4],
            'Price'
        );

        $table_angel_qoh_ticket->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['default' =>  new \Zend_Db_Expr('CURRENT_TIMESTAMP'),'nullable' => False],
            'Created At'
        );

        $table_angel_qoh_ticket->addColumn(
            'credit_transaction_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Store Credit Transaction Id'
        );

        $table_angel_qoh_ticket->addColumn(
            'card_number',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Card Number'
        );

        $table_angel_qoh_ticket->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['default' => '0','nullable' => False],
            'status'
        );


        $table_angel_qoh_ticket->addColumn(
            'serial',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => False],
            'Serial Number'
        );

        $table_angel_qoh_ticket->addIndex(
            $setup->getIdxName(
                'angel_qoh_ticket',
                ['serial'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['serial'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        );

        $table_angel_qoh_ticket->addIndex(
            $setup->getIdxName(
                'angel_qoh_ticket',
                ['product_id', 'start'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['product_id', 'start'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        );

        $table_angel_qoh_ticket->addForeignKey(
            $setup->getFkName('angel_qoh_ticket', 'product_id', 'catalog_product_entity', 'entity_id'),
            'product_id',
            $setup->getTable('catalog_product_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        );

        $table_angel_qoh_prize = $setup->getConnection()->newTable($setup->getTable('angel_qoh_prize'));

        $table_angel_qoh_prize->addColumn(
            'prize_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
            'Entity ID'
        );

        $table_angel_qoh_prize->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False,'unsigned' => true],
            'Product Id'
        );

        $table_angel_qoh_prize->addColumn(
            'winning_number',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Winning Numbers'
        );

        $table_angel_qoh_prize->addColumn(
            'ticket_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true],
            'ticket_id'
        );

        $table_angel_qoh_prize->addColumn(
            'card',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False],
            'Drawed Card'
        );

        $table_angel_qoh_prize->addColumn(
            'prize',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            ['precision' => 12,'scale' => 4],
            'Prize'
        );

        $table_angel_qoh_prize->addColumn(
            'start_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'Start At'
        );

        $table_angel_qoh_prize->addColumn(
            'end_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'End At'
        );

        $table_angel_qoh_prize->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['default' =>  new \Zend_Db_Expr('CURRENT_TIMESTAMP'),'nullable' => False],
            'Created At'
        );

        $table_angel_qoh_prize->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => False],
            'Status'
        );

        $table_angel_qoh_prize->addIndex(
            $setup->getIdxName(
                'angel_qoh_prize',
                ['product_id', 'end_at'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['product_id', 'end_at'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        );

        $table_angel_qoh_prize->addIndex(
            $setup->getIdxName(
                'angel_qoh_prize',
                ['product_id', 'card'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['product_id', 'card'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        );

        $table_angel_qoh_prize->addForeignKey(
            $setup->getFkName('angel_qoh_prize', 'product_id', 'catalog_product_entity', 'entity_id'),
            'product_id',
            $setup->getTable('catalog_product_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        );

        $table_angel_qoh_prize->addColumn(
            'card_number',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Card Number'
        );

        $table_angel_qoh_prize->addColumn(
            'transaction',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'transaction'
        );

        //Your install script

        $setup->getConnection()->createTable($table_angel_qoh_prize);

        $setup->getConnection()->createTable($table_angel_qoh_ticket);
    }
}
