<?php


namespace Angel\QoH\Model\Product\Attribute\Source;

class AdditionalTime extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    const ADD_ONE_DAY = '+1 day';
    const ADD_ONE_WEEK = '+1 week';
    const ADD_ONE_MONTH = '+1 month';
    const ADD_ONE_HOUR = '+1 hour';
    const FIVE_MINUTES = '+2 minutes';
    const TWO_MINUTES = '+2 minutes';
    const THIRTY_MINUTE = '+30 minutes';
    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['value' => self::ADD_ONE_DAY, 'label' => __('One Day')],
            ['value' => self::ADD_ONE_WEEK, 'label' => __('One Week')],
            ['value' => self::ADD_ONE_MONTH, 'label' => __('One Month')],
            ['value' => self::ADD_ONE_HOUR, 'label' => __('One Hour')],
            ['value' => self::TWO_MINUTES, 'label' => __('2 minutes(only for test)')],
            ['value' => self::FIVE_MINUTES, 'label' => __('5 minutes(only for test)')],
            ['value' => self::THIRTY_MINUTE, 'label' => __('30 minutes(only for test)')],
        ];
        return $this->_options;
    }

    /**
     * @param $product
     * @return string
     * @throws \Exception
     */
    public static function getNewEndTime($product){
        $additionalTime = $product->getData('additional_time');
        $date = new \DateTime();
//        var_dump($date->setTimestamp(strtotime($additionalTime))->format('Y/m/d H:i:s'));
//        die('234');
        return $date->setTimestamp(strtotime($additionalTime))->format('Y/m/d H:i:s');
    }
}
