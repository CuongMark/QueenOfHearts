<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 4/15/2019
 * Time: 11:25 PM
 */

namespace Angel\QoH\Api;


interface ReceiptManagementInterface
{

    /**
     * @param int $ticketId
     * @param int $customerId
     * @return \Angel\QoH\Api\Data\ReceiptInterface
     */
    public function getReceipt($ticketId, $customerId);
}