<?php


namespace Angel\QoH\Api;

interface PurchaseManagementInterface
{

    /**
     * POST for purchase api
     * @param int $product
     * @param int $qty
     * @param int $cardNumber
     * @param int $customerId
     * @return \Angel\QoH\Api\Data\TicketInterface
     */
    public function postPurchase($product, $qty, $cardNumber, $customerId);
}
