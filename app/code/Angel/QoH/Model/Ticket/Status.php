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

class Status extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    const STATUS_PENDING = 0;
    const STATUS_PAID = 1;
    const STATUS_WINNING = 2;
    const STATUS_LOSE = 3;
    const STATUS_CANCELED = 4;
    const STATUS_TRANSFERED = 5;
    const STATUS_WAITING = 6;
    const STATUS_PRINTED = 7;
    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['value' => self::STATUS_PENDING, 'label' => __('Pending')],
            ['value' => self::STATUS_PAID, 'label' => __('Paid')],
            ['value' => self::STATUS_WINNING, 'label' => __('Winning')],
            ['value' => self::STATUS_LOSE, 'label' => __('Lose')],
//            ['value' => self::STATUS_TRANSFERED, 'label' => __('Transfered to credit')],
            ['value' => self::STATUS_CANCELED, 'label' => __('Canceled')],
            ['value' => self::STATUS_WAITING, 'label' => __('Waiting')],
            ['value' => self::STATUS_PRINTED, 'label' => __('Printed')],
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
            self::STATUS_PENDING => __('Pending'),
            self::STATUS_PAID => __('Paid'),
            self::STATUS_WINNING => __('Winning'),
            self::STATUS_LOSE => __('Lose'),
//            self::STATUS_TRANSFERED => __('Transfered to credit'),
            self::STATUS_CANCELED => __('Canceled'),
            self::STATUS_WAITING => __('Waiting'),
            self::STATUS_PRINTED => __('Printed'),
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
