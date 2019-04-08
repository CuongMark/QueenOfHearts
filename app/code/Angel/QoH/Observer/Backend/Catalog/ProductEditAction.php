<?php


namespace Angel\QoH\Observer\Backend\Catalog;
use Angel\QoH\Model\QohManagement;

class ProductEditAction implements \Magento\Framework\Event\ObserverInterface
{
    private $qohManagement;

    public function __construct(
        QohManagement $qohManagement
    ){
        $this->qohManagement = $qohManagement;
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
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $observer->getEvent()->getProduct();
        $this->qohManagement->updateStatus($product);
    }
}
