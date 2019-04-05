<?php


namespace Angel\QoH\Api\Data;

interface TicketSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Ticket list.
     * @return \Angel\QoH\Api\Data\TicketInterface[]
     */
    public function getItems();

    /**
     * Set product_id list.
     * @param \Angel\QoH\Api\Data\TicketInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
