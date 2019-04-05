<?php


namespace Angel\QoH\Model\Product\Type;

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
}
