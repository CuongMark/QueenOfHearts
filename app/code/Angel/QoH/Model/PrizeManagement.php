<?php


namespace Angel\QoH\Model;

use \Angel\QoH\Model\Data\Prize as PrizeDataModel;
use Angel\QoH\Model\Prize\Status;
use Angel\QoH\Model\ResourceModel\Prize\Collection;
use Angel\QoH\Model\ResourceModel\Prize\CollectionFactory;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;

class PrizeManagement
{
    private $prizeRepository;
    private $prizeData;
    private $collectionFactory;

    public function __construct(
        PrizeRepository $prizeRepository,
        CollectionFactory $collectionFactory,
        PrizeDataModel $prizeData
    ){
        $this->prizeRepository = $prizeRepository;
        $this->prizeData = $prizeData;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param Product $product
     * @param Ticket $ticket
     * @return PrizeDataModel
     * @throws LocalizedException
     */
    public function createPrize($product, $ticket){
        $this->prizeData->setProductId($product->getId())
            ->setStartAt($product->getQohStartAt())
            ->setEndAt($product->getQohFinishAt())
            ->setWinningNumber($ticket->getWinningNumber())
            ->setPrize(0)
            ->setCard(0)
            ->setCardNumber($ticket->getCardNumber())
            ->setTicketId($ticket->getId())
            ->setTransaction('')
            ->setStatus(Status::STATUS_PENDING);
        return $this->prizeRepository->save($this->prizeData);
    }

    public function getPrizes($productId){
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('product_id', $productId);
        return $collection;
    }

    /**
     * @param int $productId
     * @return int
     */
    public function getTotalPrize($productId){
        $collection = $this->getPrizes($productId);
        $collection->getSelect()->columns(['total_prize' => 'SUM(prize)']);
        $lastItem = $collection->setPageSize(1)
            ->setCurPage(1)
            ->getFirstItem();
        if ($lastItem->getId()){
            return $lastItem->getTotalPrize();
        } else {
            return 0;
        }
    }

    /**
     * @return Collection
     */
    public function getTotalPrizeCollection(){
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToSelect(['product_id']);
        $collection->getSelect()
            ->columns(['total_prize' => 'SUM(main_table.prize)'])
            ->group('main_table.product_id');
        return $collection;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function joinTotalSales($productCollection){
        $productCollection->getSelect()->joinLeft(
            ['total_prize' => new \Zend_Db_Expr('('.$this->getTotalPrizeCollection()->getSelect()->__toString().')')],
            'e.entity_id = total_prize.product_id',
            ['total_prize' => 'total_prize.total_prize']
        );
        return $productCollection;
    }

}
