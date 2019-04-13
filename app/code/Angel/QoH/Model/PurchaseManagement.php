<?php


namespace Angel\QoH\Model;

use Angel\QoH\Model\Ticket\Status;
use Magento\Catalog\Model\Product;
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
    private $prizeManagement;
    private $ticket;
    private $customerManagement;

    public function __construct(
        ManagerInterface $message,
        Session $customerSession,
        ProductRepository $productRepository,
        \Angel\QoH\Model\Data\Ticket $ticketDataModel,
        TicketRepository $ticketRepository,
        TicketManagement $ticketManagement,
        PrizeManagement $prizeManagement,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        Ticket $ticket,
        CustomerManagement $customerManagement
    ){
        $this->messageManager = $message;
        $this->customerSession = $customerSession;
        $this->productRepository = $productRepository;
        $this->ticketDataModel = $ticketDataModel;
        $this->ticketRepository = $ticketRepository;
        $this->ticketManagement = $ticketManagement;
        $this->prizeManagement = $prizeManagement;
        $this->eventManager = $eventManager;
        $this->ticket = $ticket;
        $this->customerManagement = $customerManagement;
    }

    /**
     * {@inheritdoc}
     */
    public function postPurchase($product_id, $qty, $cardNumber, $customerId)
    {
        try {
            $this->ticket->getResource()->beginTransaction();
            if ($qty<=0){
                throw new \Exception('The Qty is not available');
            }
            if ($cardNumber < 1 || $cardNumber > 54 || in_array($cardNumber, $this->prizeManagement->getDrawnCards($product_id))){
                throw new \Exception('The Card Number is not available');
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
                ->setStatus(Status::STATUS_PENDING)
                ->setSerial($this->generateSerial());


            $this->eventManager->dispatch('angel_qoh_create_new_ticket', ['ticket' => $this->ticketDataModel, 'product' => $product]);
            $ticketData = $this->ticketRepository->save($this->ticketDataModel);

            $this->ticket->getResource()->commit();
            $this->messageManager->addSuccessMessage(__('You purchased successfully %1 tickets', $qty));
            return $ticketData;
        } catch (\Exception $e){
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->ticket->getResource()->rollBack();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function postPurchaseAdmin($product_id, $qty, $cardNumber, $customerId, $status, $customPrice = null)
    {
        try {
            $this->ticket->getResource()->beginTransaction();
            if ($qty<=0){
                throw new \Exception('The Qty is not available');
            }
            if ($cardNumber < 1 || $cardNumber > 54 || in_array($cardNumber, $this->prizeManagement->getDrawnCards($product_id))){
                throw new \Exception('The Card Number is not available');
            }
            $product = $this->productRepository->getById($product_id);
            if (! in_array($product->getQohStatus(), [\Angel\QoH\Model\Product\Attribute\Source\Status::PROCESSING, \Angel\QoH\Model\Product\Attribute\Source\Status::WAITING])){
                throw new \Exception('The Raffle is not saleable');
            }
            /** @var Ticket $lastTicket */
            $lastTicket = $this->ticketManagement->getLastTicket($product_id);
            $lastTicketNumber = $lastTicket->getEnd();

            $price = $customPrice?$customPrice:$product->getPrice() * $qty;

            $this->ticketDataModel->setStart($lastTicketNumber + 1)
                ->setEnd($lastTicketNumber + $qty)
                ->setPrice($price)
                ->setCustomerId($customerId)
                ->setProductId($product_id)
                ->setCardNumber($cardNumber)
                ->setStatus($status)
                ->setSerial($this->generateSerial());
            $ticketData = $this->ticketRepository->save($this->ticketDataModel);

            $this->ticket->getResource()->commit();
            $this->messageManager->addSuccessMessage(__('You purchased successfully %1 tickets', $qty));
            return $ticketData;
        } catch (\Exception $e){
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->ticket->getResource()->rollBack();
        }
        return $this->ticketDataModel;
    }

    /**
     * @param Product $product
     * @param $data
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     */
    public function importTicket($product, $data){
        unset($data[0]);
        if (! in_array($product->getQohStatus(), [\Angel\QoH\Model\Product\Attribute\Source\Status::PROCESSING, \Angel\QoH\Model\Product\Attribute\Source\Status::WAITING])){
            throw new \Exception('The Queen of Hearts Raffle is not able to import new ticket. The status is not processing or waiting');
        }
        /** @var Ticket $lastTicket */
        $lastTicket = $this->ticketManagement->getLastTicket($product->getId());
        $lastTicketNumber = $lastTicket->getEnd();
        $drawnCard = $this->prizeManagement->getDrawnCards($product->getId());

        /**
         * 0 - customer_emai
         * 1 - qty
         * 2 - card_number
         * 3 - customer_price
         * 4 - status
         */
        foreach ($data as $ticket){
            if ($ticket[1] <= 0){
                throw new \Exception(__('The Qty "%1" is not available'), $ticket[1]);
            }
            if ($ticket[2] < 1 || $ticket[2] > 54 || in_array((int)$ticket[2], $drawnCard)){
                throw new \Exception(__('The Card Number %1 is not available', $ticket[2]));
            }
            if (!in_array($ticket[4],[Status::STATUS_PENDING, Status::STATUS_PAID, Status::STATUS_CANCELED, Status::STATUS_WAITING])){
                throw new \Exception(__('The Status "%1" is not available', $ticket[4]));
            }

            $price = $ticket[3]?$ticket[3]:$product->getPrice() * $ticket[1];
            $customer = $this->customerManagement->getOrCreateCustomerByEmail($ticket[0]);
            $this->ticketDataModel->setStart($lastTicketNumber + 1)
                ->setEnd($lastTicketNumber + $ticket[1])
                ->setPrice($price)
                ->setCustomerId($customer->getId())
                ->setProductId($product->getId())
                ->setCardNumber($ticket[2])
                ->setStatus($ticket[4])
                ->setSerial($this->generateSerial());
            $this->ticketRepository->save($this->ticketDataModel);
            $lastTicketNumber += $ticket[1];
        }
    }

    private function generateSerial()
    {
        $characters = '0123456789';
        $randstring = '';
        for ($i = 0; $i < 13; $i++) {
            $randstring .= $characters[rand(0, strlen($characters)-1)];
        }
        return $randstring;
    }
}
