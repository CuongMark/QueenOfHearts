<?php


namespace Angel\QoH\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

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
    }
}
