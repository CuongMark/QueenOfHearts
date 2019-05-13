<?php


namespace Angel\QoH\Block\Tickets;

use Angel\QoH\Model\QohManagement;
use Angel\QoH\Model\Ticket\Status;
use Angel\QoH\Model\TicketManagement;
use \Magento\Checkout\Model\Session as CheckoutSession;
use Angel\QoH\Model\ResourceModel\Ticket\Collection;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class CheckoutSuccess extends \Magento\Framework\View\Element\Template
{
    private $checkoutSession;
    private $ticketManagement;
    /**
     * @var Collection
     */
    protected $ticketCollection;
    private $fdManagement;
    private $priceCurrency;

    /**
     * CheckoutSuccess constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param CheckoutSession $checkoutSession
     * @param TicketManagement $ticketManagement
     * @param QohManagement $fdManagement
     * @param PriceCurrencyInterface $priceCurrency
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        CheckoutSession $checkoutSession,
        TicketManagement $ticketManagement,
        QoHManagement $fdManagement,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->ticketManagement = $ticketManagement;
        $this->fdManagement = $fdManagement;
        $this->priceCurrency = $priceCurrency;
        parent::__construct($context, $data);
    }


    /**
     * @return Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getOrderedTickets(){
        if (!$this->ticketCollection) {
            $this->ticketCollection = $this->ticketManagement->getCollectionByOrderId($this->checkoutSession->getLastRealOrder()->getId());
            $this->fdManagement->joinProductName($this->ticketCollection);
        }
        return $this->ticketCollection;
    }

    /**
     * Retrieve formated price
     *
     * @param float $value
     * @return string
     */
    public function formatPrice($value, $isHtml = true)
    {
        return $this->priceCurrency->format(
            $value,
            $isHtml,
            PriceCurrencyInterface::DEFAULT_PRECISION,
            1 //Todo getStore
        );
    }

    public function getStatusLabel($ticket){
        $options = Status::getOptionArray();
        return $options[$ticket->getStatus()];
    }
}
