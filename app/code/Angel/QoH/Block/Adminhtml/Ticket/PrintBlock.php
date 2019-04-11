<?php


namespace Angel\QoH\Block\Adminhtml\Ticket;

use Angel\QoH\Model\Data\Ticket;
use Angel\QoH\Model\TicketRepository;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Model\ResourceModel\CustomerRepository;

class PrintBlock extends \Magento\Backend\Block\Template
{
    private $ticketRepository;
    private $customerRepository;
    private $productRepository;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        TicketRepository $ticketRepository,
        CustomerRepository $customerRepository,
        ProductRepository $productRepository,
        array $data = []
    ) {
        $this->ticketRepository = $ticketRepository;
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
        parent::__construct($context, $data);
    }

    /**
     * @return false|string
     */
    public function getTicketJson(){
        $data = [];
        try {
            $ticketId = $this->getRequest()->getParam('ticket_id');
            /** @var Ticket $ticket */
            $ticket = $this->ticketRepository->getById($ticketId);
            $customer = $this->customerRepository->getById($ticket->getCustomerId());
            $product = $this->productRepository->getById($ticket->getProductId());
            $data = [
                'product_sku' => $product->getSku(),
                'start' => $ticket->getStart(),
                'end' => $ticket->getEnd(),
                'card_number' => $ticket->getCardNumber(),
                'customer_email' => $customer->getEmail()
            ];
            json_encode([$data]);
        } catch (\Exception $e){
            json_encode([]);
        }
        return json_encode([$data]);
    }

}
