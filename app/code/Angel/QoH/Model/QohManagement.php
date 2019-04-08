<?php


namespace Angel\QoH\Model;


use Angel\QoH\Model\Product\Attribute\Source\Status;
use Angel\QoH\Model\Product\Type\Qoh;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class QohManagement
{
    private $ticketManagement;
    private $productRepository;
    private $productCollectionFactory;
    private $prizeRepository;
    private $prizeManagement;

    public function __construct(
        TicketManagement $ticketManagement,
        PrizeRepository $prizeRepository,
        PrizeManagement $prizeManagement,
        ProductRepository $productRepository,
        CollectionFactory $productCollectionFactory
    ){
        $this->ticketManagement = $ticketManagement;
        $this->prizeRepository = $prizeRepository;
        $this->productRepository = $productRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->prizeManagement = $prizeManagement;
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
                        $winningTicket = $this->ticketManagement->drawTicket($product);
                        $prize = $this->prizeManagement->createPrize($product, $winningTicket);
                        $this->productRepository->save($product->setQohStatus(Status::WAITING));
                    }
                }
                $product->getResource()->commit();
            }
        } catch (\Exception $e){
            $product->getResource()->rollBack();
        }

        if (isset($winningTicket)){
            //Todo send Winning Email
        }
    }

    public function massUpdateStatus(){
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToFilter('type_id', Qoh::TYPE_ID)
            ->addAttributeToFilter('qoh_status', ['in' => [Status::NOT_START, Status::PROCESSING]])
            ->addAttributeToSelect(['qoh_status', 'qoh_start_at', 'qoh_finish_at', 'qoh_start_pot']);
        foreach ($productCollection as $product){
            $this->updateStatus($product);
        }
    }
}
