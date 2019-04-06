<?php


namespace Angel\QoH\Model;


class QohManagement
{
    private $ticketManagement;

    public function __construct(
        TicketManagement $ticketManagement
    ){
        $this->ticketManagement = $ticketManagement;
    }

    public function getJackPot($product){
        return $product->getStartPot() + $this->ticketManagement->getTotalSale($product->getId());
    }
}
