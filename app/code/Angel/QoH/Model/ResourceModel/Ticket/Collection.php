<?php


namespace Angel\QoH\Model\ResourceModel\Ticket;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Angel\QoH\Model\Ticket::class,
            \Angel\QoH\Model\ResourceModel\Ticket::class
        );
    }
}
