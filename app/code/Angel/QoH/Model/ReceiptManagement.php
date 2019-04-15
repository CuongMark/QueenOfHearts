<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 4/15/2019
 * Time: 11:13 PM
 */

namespace Angel\QoH\Model;


use Angel\QoH\Model\Data\Receipt as ReceiptData;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Model\ResourceModel\CustomerRepository;

class ReceiptManagement implements \Angel\QoH\Api\ReceiptManagementInterface
{
    private $ticketRepository;
    private $receipt;
    private $productRepository;
    private $customerRepository;

    public function __construct(
        TicketRepository $ticketRepository,
        ReceiptData $receipt,
        ProductRepository $productRepository,
        CustomerRepository $customerRepository
    ){
        $this->ticketRepository = $ticketRepository;
        $this->receipt = $receipt;
        $this->productRepository = $productRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param $ticketId
     * @param $customerId
     * @return ReceiptData
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getReceipt($ticketId, $customerId){
        $ticket = $this->ticketRepository->getById($ticketId);
        if ($ticket->getCustomerId() != $customerId){
            throw new \Exception(__('The ticket does not availble'));
        }
        $product = $this->productRepository->getById($ticket->getProductId());
        $customer = $this->customerRepository->getById($ticket->getCustomerId());
        $this->receipt->setProductName($product->getName())
            ->setCustomerEmail($customer->getEmail())
            ->setTicketId($ticket->getTicketId())
            ->setStart($ticket->getStart())
            ->setEnd($ticket->getEnd())
            ->setCardNumber($ticket->getCardNumber())
            ->setCreatedAt($ticket->getCreatedAt())
            ->setSerial($ticket->getSerial())
            ->setPrice($ticket->getPrice());
        return $this->receipt;
    }
}