<?php


namespace Angel\QoH\Block\Adminhtml\Ticket\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class MassPrintButton extends GenericButton implements ButtonProviderInterface
{

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Print'),
            'class' => 'primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'angel_qoh_print_form.angel_qoh_print_form',
                                'actionName' => 'save',
                                'params' => [
                                    false
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'on_click' => ''
        ];
    }
}
