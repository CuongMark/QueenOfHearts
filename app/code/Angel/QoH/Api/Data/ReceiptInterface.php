<?php


namespace Angel\QoH\Api\Data;

interface ReceiptInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const STATUS = 'status';
    const TICKET_ID = 'ticket_id';
    const CARD_NUMBER = 'card_number';
    const PRODUCT_NAME = 'product_name';
    const START = 'start';
    const PRICE = 'price';
    const SERIAL = 'serial';
    const CUSTOMER_EMAIL = 'customer_email';
    const CREATED_AT = 'created_at';
    const END = 'end';


    /**
     * Get ticket_id
     * @return string|null
     */
    public function getTicketId();

    /**
     * Set ticket_id
     * @param string $ticketId
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setTicketId($ticketId);

    /**
     * Get product_name
     * @return string|null
     */
    public function getProductName();

    /**
     * Set product_name
     * @param string $productName
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setProductName($productName);


    /**
     * Get customer_email
     * @return string|null
     */
    public function getCustomerEmail();

    /**
     * Set customer_email
     * @param string $customerEmail
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setCustomerEmail($customerEmail);

    /**
     * Get start
     * @return string|null
     */
    public function getStart();

    /**
     * Set start
     * @param string $start
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setStart($start);

    /**
     * Get end
     * @return string|null
     */
    public function getEnd();

    /**
     * Set end
     * @param string $end
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setEnd($end);

    /**
     * Get price
     * @return string|null
     */
    public function getPrice();

    /**
     * Set price
     * @param string $price
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setPrice($price);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setCreatedAt($createdAt);


    /**
     * Get card_number
     * @return string|null
     */
    public function getCardNumber();

    /**
     * Set card_number
     * @param string $cardNumber
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setCardNumber($cardNumber);


    /**
     * Get serial
     * @return string|null
     */
    public function getSerial();

    /**
     * Set serial
     * @param string $serial
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function setSerial($serial);
}