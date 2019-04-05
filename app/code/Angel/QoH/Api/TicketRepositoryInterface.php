<?php


namespace Angel\QoH\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface TicketRepositoryInterface
{

    /**
     * Save Ticket
     * @param \Angel\QoH\Api\Data\TicketInterface $ticket
     * @return \Angel\QoH\Api\Data\TicketInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Angel\QoH\Api\Data\TicketInterface $ticket
    );

    /**
     * Retrieve Ticket
     * @param string $ticketId
     * @return \Angel\QoH\Api\Data\TicketInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($ticketId);

    /**
     * Retrieve Ticket matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Angel\QoH\Api\Data\TicketSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Ticket
     * @param \Angel\QoH\Api\Data\TicketInterface $ticket
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Angel\QoH\Api\Data\TicketInterface $ticket
    );

    /**
     * Delete Ticket by ID
     * @param string $ticketId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($ticketId);
}
