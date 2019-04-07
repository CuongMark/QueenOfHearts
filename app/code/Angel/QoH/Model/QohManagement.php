<?php


namespace Angel\QoH\Model;


use Magento\Catalog\Model\ResourceModel\Product\Collection;

class QohManagement
{
    private $ticketManagement;

    public function __construct(
        TicketManagement $ticketManagement
    ){
        $this->ticketManagement = $ticketManagement;
    }

    public function getJackPot($product){
        return $product->getData('oqh_start_pot') + $this->ticketManagement->getTotalSale($product->getId());
    }

    /**
     * @param Collection $collection
     * @return Collection
     */
    public function addJackPotToProductCollection($collection){
        $collection = $this->ticketManagement->joinTotalSales($collection);
        $collection->joinAttribute('oqh_start_pot', 'catalog_product/oqh_start_pot', 'entity_id', null, 'inner');
        $collection->getSelect()->columns(['jack_pot' => '(at_oqh_start_pot.value + ticket_total_price.total_price)']);
        return $collection;
    }
}
