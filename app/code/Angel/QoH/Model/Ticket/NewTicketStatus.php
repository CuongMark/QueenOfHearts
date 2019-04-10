<?php
/**
 * Angel Queen of Hearts
 * Copyright (C) 2018 Mark Wolf
 *
 * This file included in Angel/QoH is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Angel\QoH\Model\Ticket;

class NewTicketStatus extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['value' => Status::STATUS_PENDING, 'label' => __('Pending')],
            ['value' => Status::STATUS_PAID, 'label' => __('Paid')],
            ['value' => Status::STATUS_WAITING, 'label' => __('Waiting')],
            ['value' => Status::STATUS_CANCELED, 'label' => __('Canceled')],
        ];
        return $this->_options;
    }

    /**
     * get model option as array
     *
     * @return array
     */
    static public function getOptionArray()
    {
        return array(
            Status::STATUS_PENDING => __('Pending'),
            Status::STATUS_PAID => __('Paid'),
            Status::STATUS_WAITING => __('Waiting'),
            Status::STATUS_CANCELED => __('Canceled'),
        );
    }

    /**
     * get model option hash as array
     *
     * @return array
     */
    static public function getOptions()
    {
        $options = array();
        foreach (self::getOptionArray() as $value => $label) {
            $options[] = array(
                'value' => $value,
                'label' => $label
            );
        }
        return $options;
    }

    public function toOptionArray()
    {
        return self::getOptions();
    }
}
