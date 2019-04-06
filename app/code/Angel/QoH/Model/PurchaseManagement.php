<?php


namespace Angel\QoH\Model;

use Angel\QoH\Model\Ticket\Status;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Model\Session;
use Magento\Framework\Message\ManagerInterface;

class PurchaseManagement implements \Angel\QoH\Api\PurchaseManagementInterface
{

    private $messageManager;
    private $customerSession;
    private $productRepository;
    private $ticketDataModel;
    private $ticketRepository;
    private $ticketManagement;
    private $eventManager;

    public function __construct(
        ManagerInterface $message,
        Session $customerSession,
        ProductRepository $productRepository,
        \Angel\QoH\Model\Data\Ticket $ticketDataModel,
        TicketRepository $ticketRepository,
        TicketManagement $ticketManagement,
        \Magento\Framework\Event\ManagerInterface $eventManager
    ){
        $this->messageManager = $message;
        $this->customerSession = $customerSession;
        $this->productRepository = $productRepository;
        $this->ticketDataModel = $ticketDataModel;
        $this->ticketRepository = $ticketRepository;
        $this->ticketManagement = $ticketManagement;
        $this->eventManager = $eventManager;
    }

    /**
     * {@inheritdoc}
     */
    public function postPurchase($product_id, $qty, $cardNumber, $customerId)
    {
        try {
            if ($qty<=0){
                throw new \Exception('The Qty is not available');
            }
            if ($cardNumber < 1 || $cardNumber > 54){
                throw new \Exception('The Card Id is not available');
            }
            $product = $this->productRepository->getById($product_id);
            if ($product->getQohStatus() != \Angel\QoH\Model\Product\Attribute\Source\Status::PROCESSING){
                throw new \Exception('The Raffle is not saleable');
            }
            /** @var Ticket $lastTicket */
            $lastTicket = $this->ticketManagement->getLastTicket($product_id);
            $lastTicketNumber = $lastTicket->getEnd();

            $this->ticketDataModel->setStart($lastTicketNumber + 1)
                ->setEnd($lastTicketNumber + $qty)
                ->setPrice($product->getPrice() * $qty)
                ->setCustomerId($customerId)
                ->setProductId($product_id)
                ->setCardNumber($cardNumber)
                ->setStatus(Status::STATUS_PENDING);
            $this->eventManager->dispatch('angel_qoh_create_new_ticket', ['ticket' => $this->ticketDataModel, 'product' => $product]);
            $ticketData = $this->ticketRepository->save($this->ticketDataModel);

            $this->messageManager->addSuccessMessage(__('You purchased successfully %1 tickets', $qty));
            return $ticketData;
        } catch (\Exception $e){
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }
}
