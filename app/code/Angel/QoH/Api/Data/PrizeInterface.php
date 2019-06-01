<?php


namespace Angel\QoH\Api\Data;

interface PrizeInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const PRIZE_ID = 'prize_id';
    const STATUS = 'status';
    const WINNING_NUMBER = 'winning_number';
    const TICKET_ID = 'ticket_id';
    const START_AT = 'start_at';
    const CARD_NUMBER = 'card_number';
    const PRODUCT_ID = 'product_id';
    const CARD = 'card';
    const TRANSACTION = 'transaction';
    const END_AT = 'end_at';
    const CREATED_AT = 'created_at';
    const PRIZE = 'prize';
    const AUTO_DRAW = 'auto_draw';

    /**
     * Get prize_id
     * @return string|null
     */
    public function getPrizeId();

    /**
     * Set prize_id
     * @param string $prizeId
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setPrizeId($prizeId);

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param string $productId
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setProductId($productId);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Angel\QoH\Api\Data\PrizeExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Angel\QoH\Api\Data\PrizeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Angel\QoH\Api\Data\PrizeExtensionInterface $extensionAttributes
    );

    /**
     * Get winning_number
     * @return string|null
     */
    public function getWinningNumber();

    /**
     * Set winning_number
     * @param string $winningNumber
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setWinningNumber($winningNumber);

    /**
     * Get ticket_id
     * @return string|null
     */
    public function getTicketId();

    /**
     * Set ticket_id
     * @param string $ticketId
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setTicketId($ticketId);

    /**
     * Get card
     * @return string|null
     */
    public function getCard();

    /**
     * Set card
     * @param string $card
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setCard($card);

    /**
     * Get prize
     * @return string|null
     */
    public function getPrize();

    /**
     * Set prize
     * @param string $prize
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setPrize($prize);

    /**
     * Get start_at
     * @return string|null
     */
    public function getStartAt();

    /**
     * Set start_at
     * @param string $startAt
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setStartAt($startAt);

    /**
     * Get end_at
     * @return string|null
     */
    public function getEndAt();

    /**
     * Set end_at
     * @param string $endAt
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setEndAt($endAt);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setStatus($status);

    /**
     * Get card_number
     * @return string|null
     */
    public function getCardNumber();

    /**
     * Set card_number
     * @param string $cardNumber
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setCardNumber($cardNumber);

    /**
     * Get transaction
     * @return string|null
     */
    public function getTransaction();

    /**
     * Set transaction
     * @param string $transaction
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setTransaction($transaction);
    /**
     * Get is auto draw
     * @return boolean|null
     */
    public function getAutoDraw();

    /**
     * Set is auto draw
     * @param boolean $isAuctodraw
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setAutoDraw($isAuctodraw);
}