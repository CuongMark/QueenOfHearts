<?php


namespace Angel\QoH\Model;

use \Angel\QoH\Model\Data\Prize as PrizeDataModel;
use Angel\QoH\Model\Prize\Status;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;

class PrizeManagement
{
    private $prizeRepository;
    private $prizeData;

    public function __construct(
        PrizeRepository $prizeRepository,
        PrizeDataModel $prizeData
    ){
        $this->prizeRepository = $prizeRepository;
        $this->prizeData = $prizeData;
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
            ->setTicketId($ticket->getId())
            ->setTransaction('')
            ->setStatus(Status::STATUS_PENDING);
        return $this->prizeRepository->save($this->prizeData);
    }

}
