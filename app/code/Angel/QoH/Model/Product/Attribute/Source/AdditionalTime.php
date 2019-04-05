<?php


namespace Angel\QoH\Model\Product\Attribute\Source;

class AdditionalTime extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    const ADD_ONE_DAY = 0;
    const ADD_ONE_WEEK = 1;
    const ADD_ONE_MONTH = 2;
    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['value' => self::ADD_ONE_DAY, 'label' => __('One Day')],
            ['value' => self::ADD_ONE_WEEK, 'label' => __(' One Week')],
            ['value' => self::ADD_ONE_MONTH, 'label' => __(' One Month')]
        ];
        return $this->_options;
    }
}
