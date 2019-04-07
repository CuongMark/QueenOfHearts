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

    /**
     * @param int $productId
     * @return int
     */
    public function getTotalSale($productId){
        $collection = $this->getCollection($productId);
        $collection->getSelect()->columns(['total_sales' => 'SUM(price)']);
        $lastItem = $collection->setOrder('ticket_id')
            ->setPageSize(1)
            ->setCurPage(1)
            ->getFirstItem();
        if ($lastItem->getId()){
            return $lastItem->getTotalSales();
        } else {
            return 0;
        }
    }

    /**
     * @param int $customerId
     * @return Collection
     */
    public function getCollectionByCustomer($customerId){
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('customer_id', $customerId);
        return $collection;
    }

    /**
     * @return Collection
     */
    public function getTotalTicketPriceCollection(){
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToSelect(['product_id']);
        $collection->getSelect()
            ->columns(['total_price' => 'SUM(main_table.price)'])
            ->group('main_table.product_id');
        return $collection;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function joinTotalSales($productCollection){
        $productCollection->getSelect()->joinLeft(
            ['ticket_total_price' => new \Zend_Db_Expr('('.$this->getTotalTicketPriceCollection()->getSelect()->__toString().')')],
            'e.entity_id = ticket_total_price.product_id',
            ['total_sales' => 'ticket_total_price.total_price']
        );
        return $productCollection;
    }
}
