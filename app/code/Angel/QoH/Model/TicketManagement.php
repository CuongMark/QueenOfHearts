<?php


namespace Angel\QoH\Model;


use Angel\QoH\Model\ResourceModel\Ticket\Collection;
use Angel\QoH\Model\ResourceModel\Ticket\CollectionFactory;
use Angel\QoH\Model\Ticket\Status;
use Magento\Catalog\Model\Product;

class TicketManagement
{
    private $collectionFactory;
    private $ticketRepository;

    public function __construct(
        CollectionFactory $collectionFactory,
        TicketRepository $ticketRepository
    ){
        $this->collectionFactory = $collectionFactory;
        $this->ticketRepository = $ticketRepository;
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
     * @return Collection
     */
    public function getProcessingTicket($productId){
        return $this->getCollection($productId)
            ->addFieldToFilter('status', Status::STATUS_PAID)
            ->setOrder('ticket_id', 'ASC');
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

    /**
     * @param Product $product
     * @return \Angel\QoH\Model\Data\Ticket|\Angel\QoH\Model\Ticket Ticket
     * @throws \Exception
     */
    public function drawTicket($product){
        $processingTicket = $this->getProcessingTicket($product->getId());
        $start = $processingTicket->getFirstItem()->getStart();
        $end = $processingTicket->getLastItem()->getEnd();
        $winningNumber = mt_rand($start, $end);

        /** @var \Angel\QoH\Model\Ticket $ticket */
        foreach ($processingTicket as $ticket){
            if ($ticket->getStart() <= $winningNumber && $winningNumber <= $ticket->getEnd()){
                $ticket->setWinningNumber($winningNumber);
                $ticket->setStatus(Status::STATUS_WINNING);
                $this->ticketRepository->save($ticket->getDataModel());
                $winningTicket = $ticket;
            } else {
                $ticket->setStatus(Status::STATUS_LOSE);
                $this->ticketRepository->save($ticket->getDataModel());
            }
        }

        if (isset($winningTicket)){
            return $winningTicket;
        } else {
            throw new \Exception(__('There are not winning Ticket'));
        }
    }
}
