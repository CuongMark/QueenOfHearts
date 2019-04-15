<?php


namespace Angel\QoH\Model\Data;

use Angel\QoH\Api\Data\ReceiptInterface;

class Receipt extends \Magento\Framework\Api\AbstractExtensibleObject implements ReceiptInterface
{

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
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setTicketId($ticketId)
    {
        return $this->setData(self::TICKET_ID, $ticketId);
    }

    /**
     * Get product_name
     * @return string|null
     */
    public function getProductName()
    {
        return $this->_get(self::PRODUCT_NAME);
    }

    /**
     * Set product_name
     * @param string $productName
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setProductName($productName)
    {
        return $this->setData(self::PRODUCT_NAME, $productName);
    }
    
    /**
     * Get customer_email
     * @return string|null
     */
    public function getCustomerEmail()
    {
        return $this->_get(self::CUSTOMER_EMAIL);
    }

    /**
     * Set customer_email
     * @param string $customerEmail
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setCustomerEmail($customerEmail)
    {
        return $this->setData(self::CUSTOMER_EMAIL, $customerEmail);
    }

    /**
     * Get start
     * @return string|null
     */
    public function getStart()
    {
        return $this->_get(self::START);
    }

    /**
     * Set start
     * @param string $start
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setStart($start)
    {
        return $this->setData(self::START, $start);
    }

    /**
     * Get end
     * @return string|null
     */
    public function getEnd()
    {
        return $this->_get(self::END);
    }

    /**
     * Set end
     * @param string $end
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setEnd($end)
    {
        return $this->setData(self::END, $end);
    }

    /**
     * Get price
     * @return string|null
     */
    public function getPrice()
    {
        return $this->_get(self::PRICE);
    }

    /**
     * Set price
     * @param string $price
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setPrice($price)
    {
        return $this->setData(self::PRICE, $price);
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
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
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
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setCardNumber($cardNumber)
    {
        return $this->setData(self::CARD_NUMBER, $cardNumber);
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
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get serial
     * @return string|null
     */
    public function getSerial()
    {
        return $this->_get(self::SERIAL);
    }

    /**
     * Set serial
     * @param string $serial
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setSerial($serial)
    {
        return $this->setData(self::SERIAL, $serial);
    }
}