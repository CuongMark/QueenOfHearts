<?php


namespace Angel\QoH\Model\Data;

use Angel\QoH\Api\Data\PrizeInterface;

class Prize extends \Magento\Framework\Api\AbstractExtensibleObject implements PrizeInterface
{

    /**
     * Get prize_id
     * @return string|null
     */
    public function getPrizeId()
    {
        return $this->_get(self::PRIZE_ID);
    }

    /**
     * Set prize_id
     * @param string $prizeId
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setPrizeId($prizeId)
    {
        return $this->setData(self::PRIZE_ID, $prizeId);
    }

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId()
    {
        return $this->_get(self::PRODUCT_ID);
    }

    /**
     * Set product_id
     * @param string $productId
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Angel\QoH\Api\Data\PrizeExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Angel\QoH\Api\Data\PrizeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Angel\QoH\Api\Data\PrizeExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get winning_number
     * @return string|null
     */
    public function getWinningNumber()
    {
        return $this->_get(self::WINNING_NUMBER);
    }

    /**
     * Set winning_number
     * @param string $winningNumber
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setWinningNumber($winningNumber)
    {
        return $this->setData(self::WINNING_NUMBER, $winningNumber);
    }

    /**
     * Get ticket_id
     * @return string|null
     */
    public function getTicketId()
    {
        return $this->_get(self::TICKET_ID);
    }

    /**
     * Set ticket_id
     * @param string $ticketId
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setTicketId($ticketId)
    {
        return $this->setData(self::TICKET_ID, $ticketId);
    }

    /**
     * Get card
     * @return string|null
     */
    public function getCard()
    {
        return $this->_get(self::CARD);
    }

    /**
     * Set card
     * @param string $card
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setCard($card)
    {
        return $this->setData(self::CARD, $card);
    }

    /**
     * Get prize
     * @return string|null
     */
    public function getPrize()
    {
        return $this->_get(self::PRIZE);
    }

    /**
     * Set prize
     * @param string $prize
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setPrize($prize)
    {
        return $this->setData(self::PRIZE, $prize);
    }

    /**
     * Get start_at
     * @return string|null
     */
    public function getStartAt()
    {
        return $this->_get(self::START_AT);
    }

    /**
     * Set start_at
     * @param string $startAt
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setStartAt($startAt)
    {
        return $this->setData(self::START_AT, $startAt);
    }

    /**
     * Get end_at
     * @return string|null
     */
    public function getEndAt()
    {
        return $this->_get(self::END_AT);
    }

    /**
     * Set end_at
     * @param string $endAt
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setEndAt($endAt)
    {
        return $this->setData(self::END_AT, $endAt);
    }

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get status
     * @return string|null
     */
    public function getStatus()
    {
        return $this->_get(self::STATUS);
    }

    /**
     * Set status
     * @param string $status
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get card_number
     * @return string|null
     */
    public function getCardNumber()
    {
        return $this->_get(self::CARD_NUMBER);
    }

    /**
     * Set card_number
     * @param string $cardNumber
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setCardNumber($cardNumber)
    {
        return $this->setData(self::CARD_NUMBER, $cardNumber);
    }

    /**
     * Get transaction
     * @return string|null
     */
    public function getTransaction()
    {
        return $this->_get(self::TRANSACTION);
    }

    /**
     * Set transaction
     * @param string $transaction
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setTransaction($transaction)
    {
        return $this->setData(self::TRANSACTION, $transaction);
    }

    /**
     * Get auto draw
     * @return boolean|null
     */
    public function getAutoDraw()
    {
        return $this->_get(self::AUTO_DRAW);
    }

    /**
     * Set transaction
     * @param boolean $isAutoDraw
     * @return \Angel\QoH\Api\Data\PrizeInterface
     */
    public function setAutoDraw($isAutoDraw)
    {
        return $this->setData(self::AUTO_DRAW, $isAutoDraw);
    }
}