<?php


namespace Angel\QoH\Model;


use Angel\QoH\Model\Product\Attribute\Source\AdditionalTime;
use Angel\QoH\Model\Product\Attribute\Source\Status;
use Angel\QoH\Model\Product\Type\Qoh;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class QohManagement
{
    protected $ticketManagement;
    protected $productRepository;
    protected $productCollectionFactory;
    protected $prizeRepository;
    protected $prizeManagement;
    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    private $customerCollectionFactory;

    /**
     * QohManagement constructor.
     * @param TicketManagement $ticketManagement
     * @param PrizeRepository $prizeRepository
     * @param PrizeManagement $prizeManagement
     * @param ProductRepository $productRepository
     * @param CollectionFactory $productCollectionFactory
     * @param \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory
     */
    public function __construct(
        TicketManagement $ticketManagement,
        PrizeRepository $prizeRepository,
        PrizeManagement $prizeManagement,
        ProductRepository $productRepository,
        CollectionFactory $productCollectionFactory,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory
    ){
        $this->ticketManagement = $ticketManagement;
        $this->prizeRepository = $prizeRepository;
        $this->productRepository = $productRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->prizeManagement = $prizeManagement;
        $this->customerCollectionFactory = $customerCollectionFactory;
    }

    public function getJackPot($product){
        return $product->getData('oqh_start_pot') + $this->ticketManagement->getTotalSale($product->getId()) - $this->prizeManagement->getTotalPrize($product->getId());
    }

    /**
     * @param Collection $collection
     * @return Collection
     */
    public function addJackPotToProductCollection($collection){
        $collection = $this->ticketManagement->joinTotalSales($collection);
        $collection = $this->prizeManagement->joinTotalSales($collection);
        $collection->joinAttribute('oqh_start_pot', 'catalog_product/oqh_start_pot', 'entity_id', null, 'inner');
        $collection->getSelect()->columns(['jack_pot' => '(at_oqh_start_pot.value + IF(ticket_total_price.total_price, ticket_total_price.total_price, 0) - IF(total_prize.total_prize, total_prize.total_prize, 0))']);
        return $collection;
    }

    /**
     * @param Product $product
     * @return ResourceModel\Prize\Collection
     */
    public function getPrizes($product){
        return $this->prizeManagement->getPrizes($product->getId());
    }

    /**
     * @param Product $product
     */
    public function updateStatus($product){
        try {
            if ($product->getTypeId() == Qoh::TYPE_ID) {
                $product->getResource()->beginTransaction();

                $now = new \DateTime();
                $start_at = new \DateTime($product->getQohStartAt());
                $finish_at = new \DateTime($product->getQohFinishAt());
                if ($product->getQohStatus() == Status::NOT_START) {
                    if ($now > $start_at) {
                        $this->productRepository->save($product->setQohStatus(Status::PROCESSING));
                    } elseif ($now > $finish_at){
                        $this->productRepository->save($product->setQohStatus(Status::CANCELED));
                    }
                } elseif ($product->getQohStatus() == Status::PROCESSING){
                    if ($now < $start_at){
                        $this->productRepository->save($product->setQohStatus(Status::NOT_START));
                    } elseif ($now > $finish_at){
                        if ($this->ticketManagement->getProcessingTicket($product->getId())->getSize()) {
                            $this->ticketManagement->waittingTickets($product);
                            $this->prizeManagement->createPrize($product);
                            $this->productRepository->save($product->setQohStatus(Status::WAITING));
                        } else {
                            $product->setData('qoh_finish_at', AdditionalTime::getNewEndTime($product));
                            $this->productRepository->save($product);
                        }
                    }
                }
                $product->getResource()->commit();
            }
        } catch (\Exception $e){
            $product->getResource()->rollBack();
        }
    }

    public function massUpdateStatus(){
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToFilter('type_id', Qoh::TYPE_ID)
            ->addAttributeToFilter('qoh_status', ['in' => [Status::NOT_START, Status::PROCESSING]])
            ->addAttributeToSelect(['qoh_status', 'qoh_start_at', 'qoh_finish_at', 'qoh_start_pot', 'additional_time', 'raffle_auto_draw']);
        foreach ($productCollection as $product){
            $this->updateStatus($product);
        }
    }

    /**
     * @param \Angel\QoH\Model\ResourceModel\Prize\Collection | \Angel\QoH\Model\ResourceModel\Ticket\Collection $collection
     * @return \Angel\QoH\Model\ResourceModel\Prize\Collection | \Angel\QoH\Model\ResourceModel\Ticket\Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function joinProductName($collection){
        $productCollection = $this->productCollectionFactory->create()
            ->addAttributeToSelect(['name']);
        $productCollection->joinAttribute('name', 'catalog_product/name', 'entity_id', null, 'inner');
        $collection->getSelect()->joinLeft(['product' => new \Zend_Db_Expr('('.$productCollection->getSelect()->__toString().')')],
            "product.entity_id = main_table.product_id",
            ['product_name' => 'product.name']
        );
        return $collection;
    }

    /**
     * @param \Angel\QoH\Model\ResourceModel\Ticket\Collection $collection
     * @return \Angel\QoH\Model\ResourceModel\Ticket\Collection
     */
    public function joinCustomerEmail($collection){
        $collection->getSelect()->joinLeft(['customer' => $collection->getTable('customer_entity')],
            'customer.entity_id = main_table.customer_id',
            ['customer_email' => 'customer.email']
        );
        return $collection;
    }


    /**
     * @param \Angel\QoH\Model\ResourceModel\Prize\Collection $collection
     */
    public function joinBidderName($collection){
        $customerCollection = $this->customerCollectionFactory->create()
            ->addAttributeToSelect(['vgiss_nick_name']);
        $customerCollection->joinAttribute('vgiss_nick_name', 'customer/vgiss_nick_name', 'entity_id', null, 'inner');
        $collection->getSelect()->joinLeft(['ticket' => $collection->getTable('angel_qoh_ticket')],
            'main_table.ticket_id = ticket.ticket_id',
            ['customer_id' => 'ticket.customer_id']
        );
        $collection->getSelect()->joinLeft(['customer' => new \Zend_Db_Expr('('.$customerCollection->getSelect()->__toString().')')],
            "ticket.customer_id = customer.entity_id",
            ['vgiss_nick_name' => 'customer.vgiss_nick_name']
        );
    }
}
