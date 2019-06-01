<?php
namespace Angel\QoH\Observer\Sales;

use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote\Item;

class SetItemDropdownAttribute implements ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var Item $quoteItem */
        $quoteItem = $observer->getQuoteItem();
        $product = $observer->getProduct();
        $quoteItem->setData('card_board_number', $quoteItem->getBuyRequest()->getData('cardNumber'));
    }
}