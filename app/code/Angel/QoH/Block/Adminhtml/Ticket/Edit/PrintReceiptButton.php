<?php


namespace Angel\QoH\Block\Adminhtml\Ticket\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class PrintReceiptButton extends GenericButton implements ButtonProviderInterface
{

    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getModelId()) {
            $data = [
                'label' => __('Print Receipt'),
                'class' => 'print_receipt',
                'on_click' => 'printReceipt()',
                'sort_order' => 22,
            ];
        }
        return $data;
    }
}
