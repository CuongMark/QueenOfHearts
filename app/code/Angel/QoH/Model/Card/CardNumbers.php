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

namespace Angel\QoH\Model\Card;

use Angel\QoH\Model\ResourceModel\Prize\Collection;
use Magento\Catalog\Model\Locator\LocatorInterface;

class CardNumbers extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

//    private $locator;
//    private $collection;
//
//    public function __construct(
//        LocatorInterface $locator,
//        Collection $collection
//    ){
//        $this->locator = $locator;
//        $this->collection = $collection;
//    }

    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        $options = [];
        for ($i = 1; $i<= 54; $i++) {
            $options[] = ['value' => $i, 'label' => $i];
        }

        return $options;
    }

    /**
     * get model option as array
     *
     * @return array
     */
    static public function getOptionArray()
    {
        $options = [];
        for ($i = 1; $i<= 54; $i++) {
            $options[$i] = $i;
        }
        return $options;
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
