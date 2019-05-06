<?php


namespace Angel\QoH\Observer\Sales;


use Angel\QoH\Model\PurchaseManagement;

class InvoiceItemSaveAfter implements \Magento\Framework\Event\ObserverInterface
{

    private $purchaseManagement;

    public function __construct(
        PurchaseManagement $purchaseManagement
    ){
        $this->purchaseManagement = $purchaseManagement;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        /** @var \Magento\Sales\Model\Order\Invoice\Item $invoiceItem */
        $invoiceItem = $observer->getData('invoice_item');
        $this->purchaseManagement->createTicketByInvoiceItem($invoiceItem);
    }
}
