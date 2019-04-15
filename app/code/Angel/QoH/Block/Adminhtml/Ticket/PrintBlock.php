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

    protected $ticket;
    protected $product;
    protected $customer;
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
            $data = [
                'product_sku' => $this->getProduct()->getSku(),
                'id' => $this->getTicket()->getTicketId(),
                'start' => $this->getTicket()->getStart(),
                'end' => $this->getTicket()->getEnd(),
                'card_number' => $this->getTicket()->getCardNumber(),
                'price' => $this->getTicket()->getPrice(),
                'serial' => $this->getTicket()->getSerial(),
                'created_at' => $this->getTicket()->getCreatedAt(),
                'customer_email' => $this->getCustomer()->getEmail()
            ];
            json_encode([$data]);
        } catch (\Exception $e){
            json_encode([]);
        }
        return json_encode([$data]);
    }

    /**
     * @return \Angel\QoH\Api\Data\TicketInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getTicket(){
        if (!$this->ticket){
            $ticketId = $this->getRequest()->getParam('ticket_id');
            /** @var Ticket $ticket */
            $this->ticket = $this->ticketRepository->getById($ticketId);
        }
        return $this->ticket;
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductInterface|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProduct(){
        if (!$this->product){
            $this->product = $product = $this->productRepository->getById($this->getTicket()->getProductId());
        }
        return $this->product;
    }

    /**
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCustomer(){
        if (!$this->customer){
            $this->customer = $product = $this->customerRepository->getById($this->getTicket()->getCustomerId());
        }
        return $this->customer;
    }
}
