<?php
/**
 * Angel Raffle Raffles
 * Copyright (C) 2018 Mark Wolf
 *
 * This file included in Angel/Raffle is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Angel\QoH\Model\Product;

use Angel\QoH\Model\Product\Attribute\Source\Status;
use Angel\QoH\Model\Product\Type\Qoh;
use Magento\Catalog\Model\Product;

class SelectProducts extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    private $collectionFactory;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
    ){
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->collectionFactory->create()
            ->addAttributeToSelect('name')
            ->addAttributeToFilter('type_id', Qoh::TYPE_ID)
            ->addAttributeToFilter('qoh_status', ['in' => [Status::PROCESSING, Status::WAITING]]);
        $collection->setOrder('e.entity_id');
        $collection->getSelect()->order('e.entity_id DESC');
        $result = [];
        /** @var Product $product */
        foreach ($collection as $product){
            $result[] = ['value' => $product->getId() , 'label' => $product->getName()];
        }
        return $result;
    }

    /**
     * get model option as array
     *
     * @return array
     */
    public function getOptionArray()
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->collectionFactory->create()
            ->addAttributeToSelect('name')
            ->addAttributeToFilter('type_id', Qoh::TYPE_ID)
            ->addAttributeToFilter('qoh_status', ['in' => [Status::PROCESSING, Status::WAITING]]);
        $collection->setOrder('e.entity_id');
        $collection->getSelect()->order('e.entity_id DESC');
        $result = [];
        /** @var Product $product */
        foreach ($collection as $product){
            $result[$product->getId()] = $product->getName();
        }
        return $result;
    }

}
