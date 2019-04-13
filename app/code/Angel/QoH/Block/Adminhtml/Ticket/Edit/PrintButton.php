<?php


namespace Angel\QoH\Block\Adminhtml\Ticket\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class PrintButton extends GenericButton implements ButtonProviderInterface
{

    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getModelId()) {
            $data = [
                'label' => __('Print'),
                'class' => 'delete',
                'on_click' => 'printTicket()',
                'sort_order' => 22,
            ];
        }
        return $data;
    }

    /**
     * Get URL for delete button
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/print', ['ticket_id' => $this->getModelId()]);
    }
}
