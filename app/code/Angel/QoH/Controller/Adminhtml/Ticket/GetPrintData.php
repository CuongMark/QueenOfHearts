<?php


namespace Angel\QoH\Controller\Adminhtml\Ticket;

use Angel\QoH\Model\ResourceModel\Ticket\Collection;
use Angel\QoH\Model\Ticket;
use Angel\QoH\Model\Ticket\Status;
use Angel\QoH\Model\TicketRepository;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Payment\Gateway\Http\Client\Zend;

class GetPrintData extends \Magento\Backend\App\Action
{

    protected $resultPageFactory;
    protected $jsonHelper;
    private $collection;
    private $customerRepository;
    private $productRepository;
    private $ticketRepository;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context  $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        Collection $collection,
        CustomerRepository $customerRepository,
        ProductRepository $productRepository,
        TicketRepository $ticketRepository
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->collection = $collection;
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
        $this->ticketRepository = $ticketRepository;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            if ($selected = $this->getRequest()->getParam('selected')){
                $tickets = $this->getPrintTicket($selected);
            } elseif ($this->getRequest()->getParam('excluded') === 'false'){
                $tickets = $this->getPrintTicket();
            } else {
                $tickets = [];
            }
            return $this->jsonResponse($tickets);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return $this->jsonResponse($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $this->jsonResponse($e->getMessage());
        }
    }

    /**
     * @param null $ticketIds
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPrintTicket($ticketIds = null){
        $this->collection->addFieldToFilter('status', ['in' => [Status::STATUS_WAITING, Status::STATUS_PAID]]);
        if ($ticketIds){
            $this->collection->addFieldToFilter('ticket_id', ['in', $ticketIds]);
        }
        $ticketsData = [];
        $status = $this->getRequest()->getParam('status');

        /** @var Ticket $ticket */
        foreach ($this->collection as $ticket){
            $customer = $this->customerRepository->getById($ticket->getCustomerId());
            $product = $this->productRepository->getById($ticket->getProductId());
            $data = [
                'product_sku' => $product->getSku(),
                'start' => $ticket->getStart(),
                'end' => $ticket->getEnd(),
                'card_number' => $ticket->getCardNumber(),
                'customer_email' => $customer->getEmail()
            ];
            $ticketsData[] = $data;
            if (in_array($status, [Status::STATUS_PAID, Status::STATUS_WAITING, Status::STATUS_PRINTED]) && $ticket->getStatus() != $status){
                $ticket->setStatus($status);
                $this->ticketRepository->save($ticket->getDataModel());
            }
        }
        return $ticketsData;
    }

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }
}