<?php


namespace Angel\QoH\Model\ResourceModel;

class Prize extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('angel_qoh_prize', 'prize_id');
    }
}
