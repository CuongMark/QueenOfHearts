<?php


namespace Angel\QoH\Api\Data;

interface TicketInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const STATUS = 'status';
    const TICKET_ID = 'ticket_id';
    const CARD_NUMBER = 'card_number';
    const PRODUCT_ID = 'product_id';
    const START = 'start';
    const CREDIT_TRANSACTION_ID = 'credit_transaction_id';
    const PRICE = 'price';
    const SERIAL = 'serial';
    const CUSTOMER_ID = 'customer_id';
    const CREATED_AT = 'created_at';
    const END = 'end';
    const INVOICE_ITEM_ID = 'invoice_item_id';

    /**
     * Get ticket_id
     * @return string|null
     */
    public function getTicketId();

    /**
     * Set ticket_id
     * @param string $ticketId
     * @return \Angel\QoH\Api\Data\TicketInterface
     */
    public function setTicketId($ticketId);

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param string $productId
     * @return \Angel\QoH\Api\Data\TicketInterface
     */
    public function setProductId($productId);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Angel\QoH\Api\Data\TicketExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Angel\QoH\Api\Data\TicketExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Angel\QoH\Api\Data\TicketExtensionInterface $extensionAttributes
    );

    /**
     * Get customer_id
     * @return string|null
     */
    public function getCustomerId();

    /**
     * Set customer_id
     * @param string $customerId
     * @return \Angel\QoH\Api\Data\TicketInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get start
     * @return string|null
     */
    public function getStart();

    /**
     * Set start
     * @param string $start
     * @return \Angel\QoH\Api\Data\TicketInterface
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
     * @return \Angel\QoH\Api\Data\TicketInterface
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
     * @return \Angel\QoH\Api\Data\TicketInterface
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
     * @return \Angel\QoH\Api\Data\TicketInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get credit_transaction_id
     * @return string|null
     */
    public function getCreditTransactionId();

    /**
     * Set credit_transaction_id
     * @param string $creditTransactionId
     * @return \Angel\QoH\Api\Data\TicketInterface
     */
    public function setCreditTransactionId($creditTransactionId);

    /**
     * Get card_number
     * @return string|null
     */
    public function getCardNumber();

    /**
     * Set card_number
     * @param string $cardNumber
     * @return \Angel\QoH\Api\Data\TicketInterface
     */
    public function setCardNumber($cardNumber);

    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \Angel\QoH\Api\Data\TicketInterface
     */
    public function setStatus($status);

    /**
     * Get serial
     * @return string|null
     */
    public function getSerial();

    /**
     * Set serial
     * @param string $serial
     * @return \Angel\QoH\Api\Data\TicketInterface
     */
    public function setSerial($serial);

    /**
     * Get invoice_item_id
     * @return string|null
     */
    public function getInvoiceItemId();

    /**
     * Set invoice_item_id
     * @param string $invoiceItemId
     * @return \Angel\QoH\Api\Data\TicketInterface
     */
    public function setInvoiceItemId($invoiceItemId);
}