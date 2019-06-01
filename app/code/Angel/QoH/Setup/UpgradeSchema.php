<?php


namespace Angel\QoH\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    const ORDER_ITEM_TABLE = 'sales_order_item';
    /**
     * {@inheritdoc}
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        if (version_compare($context->getVersion(), "1.0.1", "<")) {
            if (!$setup->getConnection()->tableColumnExists($setup->getTable('angel_qoh_ticket'), 'invoice_item_id')) {
                $setup->getConnection()->addColumn(
                    $setup->getTable('angel_qoh_ticket'),
                    'invoice_item_id',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        'nullable' => false,
                        'default' => '0',
                        'comment' => 'Invoice Item Id'
                    ]
                );
            }
        }

        if (version_compare($context->getVersion(), "1.0.2", "<")) {
            if (!$setup->getConnection()->tableColumnExists($setup->getTable('angel_qoh_prize'), 'auto_draw')) {
                $setup->getConnection()->addColumn(
                    $setup->getTable('angel_qoh_prize'),
                    'auto_draw',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        'nullable' => false,
                        'default' => '0',
                        'comment' => 'Is Auto Draw'
                    ]
                );
            }

            /* Add column for order item table*/
            if (!$setup->getConnection()->tableColumnExists($setup->getTable(self::ORDER_ITEM_TABLE), 'card_board_number')) {
                $setup->getConnection()->addColumn(
                    $setup->getTable(self::ORDER_ITEM_TABLE),
                    'card_board_number',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        'nullable' => false,
                        'default' => '0',
                        'comment' => 'Card Board Number'
                    ]
                );
            }
        }
    }
}
