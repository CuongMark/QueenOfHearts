<?php


namespace Angel\QoH\Model;


use Angel\QoH\Model\Prize\Status;
use Angel\QoH\Model\Product\Attribute\Source\AdditionalTime;
use Angel\QoH\Model\ResourceModel\Prize\CollectionFactory;
use Angel\QoH\Model\Card\Options as CardOptions;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductRepository;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Angel\QoH\Model\ResourceModel\Ticket\Collection;

class DrawCard
{
    protected $prizeCollectionFactory;
    protected $ticketCollection;
    protected $qohManagement;
    protected $productRepository;
    protected $ticketManagement;
    protected $prizeRepository;
    protected $timezone;

    public function __construct(
        CollectionFactory $prizeCollectionFactory,
        Collection $ticketCollection,
        QohManagement $qohManagement,
        ProductRepository $productRepository,
        TicketManagement $ticketManagement,
        PrizeRepository $prizeRepository,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
    ){
        $this->prizeCollectionFactory = $prizeCollectionFactory;
        $this->ticketCollection = $ticketCollection;
        $this->qohManagement = $qohManagement;
        $this->productRepository = $productRepository;
        $this->ticketManagement = $ticketManagement;
        $this->prizeRepository = $prizeRepository;
        $this->timezone = $timezone;
    }

    /**
     * @throws \Exception
     */
    public function massDrawCard(){
        $prizes = $this->prizeCollectionFactory->create()
            ->addFieldToFilter('status', Status::STATUS_PENDING)
            ->addFieldToFilter('auto_draw', 1);
        foreach ($prizes as $prize) {
            $this->drawCard($prize);
        }
    }

    /**
     * @param \Angel\QoH\Model\Data\Prize|\Angel\QoH\Model\Prize $prize
     * @throws \Exception
     */
    public function drawCard($prize){
        if ($prize->getStatus() != Status::STATUS_PENDING && $prize->getAutoDraw()) {
            throw new \Exception(__('Unable to draw card'));
        }

        $waitingTicket = $this->getWaitingTickets($prize->getProductId());
        if (!$waitingTicket->getSize()){
            $prize->setStatus(Status::STATUS_CANCELED);
            $this->prizeRepository->save($prize->getDataModel());
            $newStatus = \Angel\QoH\Model\Product\Attribute\Source\Status::PROCESSING;
            $product = $this->productRepository->getById($prize->getProductId());
            $product->setData('qoh_status', $newStatus);
            $this->productRepository->save($product);
            return;
        }

        $winningNumber = $this->getWinningNumber($prize);
        $card = $this->getCardNumber($prize);
        /** @var Product $product */
        $product = $this->productRepository->getById($prize->getProductId());
        $winningPrize = $this->getWinningPrize($card, $product);
        $prize->setWinningNumber($winningNumber)
            ->setCard($card)
            ->setPrize($winningPrize);

        try {
            $product->getResource()->beginTransaction();

            /** update ticket status and set winning ticket id to prize */
            // TODO use update query to speed up
            $this->ticketManagement->winningTickets($prize);
            // TODO transfer to credit
            $prize->setStatus(Status::STATUS_PROCESSING);
            $this->prizeRepository->save($prize->getDataModel());
            $this->updateQohStatus($card, $product);
            // TODO send email to player

            $product->getResource()->commit();
        } catch (\Exception $e){
            $product->getResource()->rollBack();
        }
    }

    /**
     * @param int $card
     * @param Product $product
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function updateQohStatus($card, $product){
        if (CardOptions::isQoH($card)){
            $newStatus = \Angel\QoH\Model\Product\Attribute\Source\Status::FINISHED;
            $product->setData('qoh_status', $newStatus);
        } else {
            $newStatus = \Angel\QoH\Model\Product\Attribute\Source\Status::PROCESSING;
            $product->setData('qoh_status', $newStatus);

            $date = new \DateTime();
            $newStartTime = $date->format('Y/m/d H:i:s');
            $newEndTime = AdditionalTime::getNewEndTime($product);
            $product->setData('qoh_start_at', $newStartTime);
            $product->setData('qoh_finish_at', $newEndTime);
        }
        $this->productRepository->save($product);
    }

    /**
     * @param \Angel\QoH\Model\Data\Prize $prize
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getWinningNumber($prize){
        $waitingTicket = $this->getWaitingTickets($prize->getProductId());
        $start = $waitingTicket->getFirstItem()->getStart();
        $end = $waitingTicket->getLastItem()->getEnd();
        return RandomNumberGenerate::getWinningNumber($start, $end);
    }

    /**
     * @param \Angel\QoH\Model\Data\Prize $prize
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCardNumber($prize){
        $drawnCard = $this->getDrawnCards($prize->getProductId());
        return RandomNumberGenerate::drawCard($drawnCard);
    }

    /**
     * @param $product_id
     * @return Collection
     */
    public function getWaitingTickets($product_id){
        return $this->ticketCollection->addFieldToFilter('product_id', $product_id)
            ->addFieldToFilter('status', \Angel\QoH\Model\Ticket\Status::STATUS_WAITING)
            ->addOrder('end', 'ASC');
    }

    /**
     * @param $card
     * @param Product $product
     * @return float|int
     * @throws LocalizedException
     */
    public function getWinningPrize($card, $product){
        if (CardOptions::isQoH($card)){
            $jackPot = $this->qohManagement->getJackPot($product);
            $winningPercent = (int)$product->getData('qoh_queen_prize');
            if ($winningPercent < 0 || $winningPercent > 100){
                throw new LocalizedException(new Phrase('Invalid range given.'));
            }
            return $jackPot * $winningPercent / 100;
        } else if (CardOptions::isFaceCard($card) || CardOptions::isACard($card)){
            return (float)$product->getData('qoh_face_prize');
        } else if (CardOptions::isJocker($card)){
            return (float)$product->getData('qoh_jokers_prize');
        } else if (CardOptions::isNumberCard($card)){
            return (float)$product->getData('qoh_number_prize');
        } else {
            return 0;
        }
    }

    /**
     * @param int $productId
     * @return array
     */
    public function getDrawnCards($productId){
        $prizes = $this->prizeCollectionFactory->create()->addFieldToFilter('product_id', $productId);
        $cards = [];
        /** @var \Angel\QoH\Model\Data\Prize $prize */
        foreach ($prizes as $prize){
            $cards[] = $prize->getCardNumber();
        }
        return $cards;
    }
}
