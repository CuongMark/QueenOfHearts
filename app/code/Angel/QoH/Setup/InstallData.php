<?php


namespace Angel\QoH\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallData implements InstallDataInterface
{

    private $eavSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'qoh_start_at',
            [
                'type' => 'datetime',
                'backend' => '',
                'frontend' => '',
                'label' => 'Start At',
                'input' => 'date',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => null,
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
        

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'qoh_finish_at',
            [
                'type' => 'datetime',
                'backend' => '',
                'frontend' => '',
                'label' => 'Finish At',
                'input' => 'date',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => null,
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
        

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'qoh_status',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Status',
                'input' => 'select',
                'class' => '',
                'source' => \Angel\QoH\Model\Product\Attribute\Source\Status::class,
                'global' => 1,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => 'qoh',
                'system' => 1,
                'group' => 'General',
                'option' => []
            ]
        );
        

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'additional_time',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Additional Time',
                'input' => 'select',
                'class' => '',
                'source' => \Angel\QoH\Model\Product\Attribute\Source\AdditionalTime::class,
                'global' => 1,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => 'qoh',
                'system' => 1,
                'group' => 'General',
                'option' => []
            ]
        );
        

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'oqh_start_pot',
            [
                'type' => 'decimal',
                'backend' => '',
                'frontend' => '',
                'label' => 'Start Pot',
                'input' => 'price',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => null,
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
        

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'qoh_queen_prize',
            [
                'type' => 'decimal',
                'backend' => '',
                'frontend' => '',
                'label' => 'Queen of Hearts Prize',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => null,
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
        

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'qoh_jokers_prize',
            [
                'type' => 'decimal',
                'backend' => '',
                'frontend' => '',
                'label' => 'Jokers Prize',
                'input' => 'price',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => null,
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
        

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'qoh_face_prize',
            [
                'type' => 'decimal',
                'backend' => '',
                'frontend' => '',
                'label' => 'Face Cards or Aces Cards Prize',
                'input' => 'price',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => null,
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
        

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'qoh_number_prize',
            [
                'type' => 'decimal',
                'backend' => '',
                'frontend' => '',
                'label' => 'Number Cards Prize',
                'input' => 'price',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => true,
                'user_defined' => false,
                'default' => null,
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
        

        //Your install script

        // associate these attributes with new product type
        $fieldList = [
            'price',
//            'special_price',
//            'special_from_date',
//            'special_to_date',
//            'minimal_price',
//            'cost',
//            'tier_price',
//            'weight',
        ];
        
        // make these attributes applicable to new product type
        foreach ($fieldList as $field) {
            $applyTo = explode(
                ',',
                $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $field, 'apply_to')
            );
            if (!in_array(\Angel\QoH\Model\Product\Type\Qoh::TYPE_ID, $applyTo)) {
                $applyTo[] = \Angel\QoH\Model\Product\Type\Qoh::TYPE_ID;
                $eavSetup->updateAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    $field,
                    'apply_to',
                    implode(',', $applyTo)
                );
            }
        }
    }
}
