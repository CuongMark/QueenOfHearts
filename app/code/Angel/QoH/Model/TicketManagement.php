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
            ->addFieldToFilter('status', ['in' => [Status::STATUS_PAID, Status::STATUS_WAITING, Status::STATUS_PRINTED]])
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
     * @param Prize $prize
     * @return \Angel\QoH\Model\Data\Ticket|\Angel\QoH\Model\Ticket Ticket
     * @throws \Exception
     */
    public function winningTickets($prize){
        $processingTicket = $this->getProcessingTicket($prize->getProductId());
        /** @var \Angel\QoH\Model\Ticket $ticket */
        foreach ($processingTicket as $ticket){
            if ($ticket->getStart() <= $prize->getWinningNumber() && $prize->getWinningNumber() <= $ticket->getEnd()){
                $ticket->setWinningNumber($prize->getWinningNumber());
                $ticket->setStatus(Status::STATUS_WINNING);
                $winningTicket = $this->ticketRepository->save($ticket->getDataModel());
                if (!$prize->getTicketId()){
                    $prize->setTicketId($winningTicket->getTicketId());
                }
            } else {
                $ticket->setStatus(Status::STATUS_LOSE);
                $this->ticketRepository->save($ticket->getDataModel());
            }
        }
    }

    /**
     * @param Prize $prize
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function cancelTickets($prize){
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('product_id', $prize->getProductId())
            ->addFieldToFilter('status', ['in' => [Status::STATUS_CANCELED, Status::STATUS_WAITING, Status::STATUS_LOSE, Status::STATUS_WINNING]]);
        /** @var \Angel\QoH\Model\Ticket $ticket */
        foreach ($collection as $ticket){
            if ($ticket->getStart() <= $prize->getWinningNumber() && $prize->getWinningNumber() <= $ticket->getEnd()){
                if ($ticket->getStatus() != Status::STATUS_CANCELED) {
                    $ticket->setWinningNumber($prize->getWinningNumber());
                    $ticket->setStatus(Status::STATUS_CANCELED);
                    $this->ticketRepository->save($ticket->getDataModel());
                }
            } else {
                if ($ticket->getStatus() != Status::STATUS_LOSE) {
                    $ticket->setStatus(Status::STATUS_LOSE);
                    $this->ticketRepository->save($ticket->getDataModel());
                }
            }
        }
    }

    /**
     * @param Product $product
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function waittingTickets($product){
        $processingTicket = $this->getProcessingTicket($product->getId());
        /** @var \Angel\QoH\Model\Ticket $ticket */
        foreach ($processingTicket as $ticket){
            $ticket->setStatus(Status::STATUS_WAITING);
            $this->ticketRepository->save($ticket->getDataModel());
        }
    }
}
