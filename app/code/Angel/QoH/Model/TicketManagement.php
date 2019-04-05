<?php


namespace Angel\QoH\Model;


use Angel\QoH\Model\ResourceModel\Ticket\Collection;
use Angel\QoH\Model\ResourceModel\Ticket\CollectionFactory;

class TicketManagement
{
    private $collectionFactory;

    public function __construct(
        CollectionFactory $collectionFactory
    ){
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param int $productId
     * @return Collection
     */
    public function getCollection($productId){
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('product_id', $productId);
        return $collection;
    }

    /**
     * @param int $productId
     * @return \Magento\Framework\DataObject
     */
    public function getLastTicket($productId){
        return $this->getCollection($productId)
            ->setOrder('ticket_id')
            ->setPageSize(1)
            ->setCurPage(1)
            ->getFirstItem();
    }
}
