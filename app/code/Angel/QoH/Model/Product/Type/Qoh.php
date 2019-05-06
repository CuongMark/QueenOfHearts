<?php


namespace Angel\QoH\Model\Product\Type;

use Angel\QoH\Model\Product\Attribute\Source\Status;

class Qoh extends \Magento\Catalog\Model\Product\Type\Virtual
{

    const TYPE_ID = 'qoh';

    /**
     * {@inheritdoc}
     */
    public function deleteTypeSpecificData(\Magento\Catalog\Model\Product $product)
    {
        // method intentionally empty
    }

    public function isSalable($product)
    {
        if ($product->getQohStatus() != Status::PROCESSING){
            return false;
        }
        return parent::isSalable($product);
    }

    public function hasOptions($product)
    {
        return true;
//        return parent::hasOptions($product);
    }
}
