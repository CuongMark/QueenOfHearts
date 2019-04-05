<?php


namespace Angel\QoH\Model\Product\Attribute\Source;

class Status extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    const NOT_START = 0;
    const PROCESSING = 1;
    const WAITING = 2;
    const FINISHED = 3;
    const CANCELED = 4;
    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['value' => self::NOT_START, 'label' => __('Not Start')],
            ['value' => self::PROCESSING, 'label' => __('Processing')],
            ['value' => self::WAITING, 'label' => __('Waiting')],
            ['value' => self::FINISHED, 'label' => __('Finished')],
            ['value' => self::CANCELED, 'label' => __('Canceled')]
        ];
        return $this->_options;
    }
}
