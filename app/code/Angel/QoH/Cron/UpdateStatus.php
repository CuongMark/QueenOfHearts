<?php
/**
 * Angel Fifty Raffles
 * Copyright (C) 2018 Mark Wolf
 *
 * This file included in Angel/Fifty is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Angel\QoH\Cron;

use Angel\QoH\Model\Product\Attribute\Source\Status;
use Angel\QoH\Model\QohManagement;
use Magento\Catalog\Model\Product;

class UpdateStatus
{

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTimeFactory
     */
    protected $dateTimeFactory;
    private $qohManagement;

    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Stdlib\DateTime\DateTimeFactory $dateTimeFactory,
        QohManagement $qohManagement
    ){
        $this->logger = $logger;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->dateTimeFactory = $dateTimeFactory;
        $this->qohManagement = $qohManagement;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->productCollectionFactory->create()
            ->addAttributeToFilter('type_id', \Angel\QoH\Model\Product\Type\Qoh::TYPE_ID)
            ->addAttributeToFilter('qoh_status', ['nin' => [Status::FINISHED, Status::CANCELED, Status::WAITING]])
            ->addAttributeToSelect('*');
        /** @var Product $product */
        foreach ($collection as $product){
            $this->qohManagement->updateStatus($product);
//            $this->logger->notice('Cronjob update QoH Status is executed at '.$now);
        }

    }
}
