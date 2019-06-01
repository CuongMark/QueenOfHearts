<?php


namespace Angel\QoH\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Quote\Setup\QuoteSetup;
use Magento\Sales\Setup\SalesSetup;
use Magento\Sales\Setup\SalesSetupFactory;
use Magento\Quote\Setup\QuoteSetupFactory;

class UpgradeData implements UpgradeDataInterface
{

    private $eavSetupFactory;
    /**
     * @var QuoteSetupFactory
     */
    private $quoteSetupFactory;

    /**
     * @var SalesSetup
     */
    private $salesSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        QuoteSetupFactory $quoteSetupFactory,
        SalesSetupFactory $salesSetupFactory
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->quoteSetupFactory = $quoteSetupFactory;
        $this->salesSetupFactory = $salesSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        if (version_compare($context->getVersion(), "1.0.2", "<")) {

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'raffle_auto_draw',
                [
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Auto Draw',
                    'input' => 'boolean',
                    'class' => '',
                    'source' => '',
                    'global' => 1,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => 0,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply_to' => 'qoh',
                    'system' => 1,
                    'group' => 'General',
                    'option' => ['values' => [""]]
                ]
            );

            /** @var QuoteSetup $quoteSetup */
            $quoteSetup = $this->quoteSetupFactory->create(['setup' => $setup]);
            /** @var SalesSetup $salesSetup */
            $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);

            $attributeOptions = [
                'type'     => Table::TYPE_SMALLINT,
                'visible'  => true,
                'required' => false
            ];
            $quoteSetup->addAttribute('quote_item', 'card_board_number', $attributeOptions);
            $salesSetup->addAttribute('order_item', 'card_board_number', $attributeOptions);

        }

        if (version_compare($context->getVersion(), "1.0.0", "<")) {
            //Your upgrade script
        }
    }
}