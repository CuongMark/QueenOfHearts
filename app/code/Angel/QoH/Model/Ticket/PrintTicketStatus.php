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

class PrintTicketStatus extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['value' => '', 'label' => __('Don\'t update')],
            ['value' => Status::STATUS_WAITING, 'label' => __('Waiting')],
            ['value' => Status::STATUS_PRINTED, 'label' => __('Printed')],
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
            '' => __('Don\'t Update'),
            Status::STATUS_WAITING => __('Waiting'),
            Status::STATUS_PRINTED => __('Printed'),
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
