<?php


namespace Angel\QoH\Model;

use Angel\QoH\Model\Product\Type\Qoh;
use Angel\QoH\Model\Ticket\Status;
use Angel\QoH\Service\Email;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Model\Session;
use Magento\Framework\DataObject;
use Magento\Framework\Message\ManagerInterface;
use Magento\Sales\Model\Order\Invoice\Item;

class PurchaseManagement implements \Angel\QoH\Api\PurchaseManagementInterface
{

    /**
     * @var ManagerInterface
     */
    protected $messageManager;
    /**
     * @var Session
     */
    protected $customerSession;
    /**
     * @var ProductRepository
     */
    protected $productRepository;
    /**
     * @var Data\Ticket
     */
    protected $ticketDataModel;
    /**
     * @var TicketRepository
     */
    protected $ticketRepository;
    /**
     * @var TicketManagement
     */
    protected $ticketManagement;
    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;
    /**
     * @var PrizeManagement
     */
    protected $prizeManagement;
    /**
     * @var Ticket
     */
    protected $ticket;
    /**
     * @var CustomerManagement
     */
    protected $customerManagement;
    /**
     * @var Email
     */
    protected $emailService;

    /**
     * @var QohManagement
     */
    protected $qohManagement;

    /**
     * PurchaseManagement constructor.
     * @param ManagerInterface $message
     * @param Session $customerSession
     * @param ProductRepository $productRepository
     * @param Data\Ticket $ticketDataModel
     * @param TicketRepository $ticketRepository
     * @param TicketManagement $ticketManagement
     * @param PrizeManagement $prizeManagement
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param Ticket $ticket
     * @param CustomerManagement $customerManagement
     * @param Email $emailService
     */
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
        CustomerManagement $customerManagement,
        Email $emailService,
        QohManagement $qohManagement
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
        $this->emailService = $emailService;
        $this->qohManagement = $qohManagement;
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

            $this->qohManagement->updateStatus($product);

            if ($product->getQohStatus() != \Angel\QoH\Model\Product\Attribute\Source\Status::PROCESSING){
                throw new \Exception('The Raffle is not saleable');
            }
            if (!$this->customerManagement->isInPurchaseGroup($customerId)){
                throw new \Exception('You are not a member');
            }
            /** @var Ticket $lastTicket */
            $lastTicket = $this->ticketManagement->getLastTicket($product_id);
            $lastTicketNumber = $lastTicket->getEnd();

            $freeTickets = new DataObject(['free_ticket' => 0]);
            $this->eventManager->dispatch('angel_get_free_ticket', ['product' => $product, 'qty' => $qty, 'free' => $freeTickets]);
            $freeTickets = $freeTickets->getData('free_ticket');

            $fee = new DataObject(['extra_fee' => 0]);
            $this->eventManager->dispatch('angel_get_extra_fee', ['product' => $product, 'qty' => $qty, 'fee' => $fee]);
            $fee = $fee->getData('extra_fee');

            $this->ticketDataModel->setStart($lastTicketNumber + 1)
                ->setEnd($lastTicketNumber + $qty + $freeTickets)
                ->setPrice($product->getPrice() * $qty + $fee)
                ->setCustomerId($customerId)
                ->setProductId($product_id)
                ->setCardNumber($cardNumber)
                ->setStatus(Status::STATUS_PENDING)
                ->setSerial($this->generateSerial());


            $this->eventManager->dispatch('angel_qoh_create_new_ticket', ['ticket' => $this->ticketDataModel, 'product' => $product]);
            $ticketData = $this->ticketRepository->save($this->ticketDataModel);
            $this->emailService->sendNewTicketEmail($product, $this->ticketDataModel);

            $this->ticket->getResource()->commit();
            if (!$freeTickets){
                $this->messageManager->addSuccessMessage(__('You purchased successfully %1 ticket(s)', $qty));
            } else {
                $this->messageManager->addSuccessMessage(__('You purchased successfully %1 tickets and get %2 free ticket(s)', $qty, $freeTickets));
            }

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
            if (!$this->customerManagement->isInPurchaseGroup($customerId)){
                throw new \Exception('You are not a member');
            }
            /** @var Ticket $lastTicket */
            $lastTicket = $this->ticketManagement->getLastTicket($product_id);
            $lastTicketNumber = $lastTicket->getEnd();

            $price = $customPrice?$customPrice:$product->getPrice() * $qty;

            $freeTickets = new DataObject(['free_ticket' => 0]);
            $this->eventManager->dispatch('angel_get_free_ticket', ['product' => $product, 'qty' => $qty, 'free' => $freeTickets]);
            $freeTickets = $freeTickets->getData('free_ticket');

            $fee = new DataObject(['extra_fee' => 0]);
            $this->eventManager->dispatch('angel_get_extra_fee', ['product' => $product, 'qty' => $qty, 'fee' => $fee]);
            $fee = $fee->getData('extra_fee');

            $this->ticketDataModel->setStart($lastTicketNumber + 1)
                ->setEnd($lastTicketNumber + $qty + $freeTickets)
                ->setPrice($price + $fee)
                ->setCustomerId($customerId)
                ->setProductId($product_id)
                ->setCardNumber($cardNumber)
                ->setStatus($status)
                ->setSerial($this->generateSerial());
            $ticketData = $this->ticketRepository->save($this->ticketDataModel);
            $this->emailService->sendNewTicketEmail($product, $this->ticketDataModel);
            $this->ticket->getResource()->commit();
            if (!$freeTickets){
                $this->messageManager->addSuccessMessage(__('You purchased successfully %1 ticket(s)', $qty));
            } else {
                $this->messageManager->addSuccessMessage(__('You purchased successfully %1 tickets and get %2 free ticket(s)', $qty, $freeTickets));
            }
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
                throw new \Exception(__('The Qty "%1" is not available', $ticket[1]));
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


    /**
     * @param Item $invoiceItem
     * @return \Angel\QoH\Api\Data\TicketInterface|Data\Ticket|bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function createTicketByInvoiceItem($invoiceItem)
    {
        $product = $this->productRepository->getById($invoiceItem->getProductId());
        if ($product->getTypeId()!=Qoh::TYPE_ID){
            return true;
        }
        $this->qohManagement->updateStatus($product);
        try {
            $this->ticket->getResource()->beginTransaction();
            $qty = $invoiceItem->getQty();

            $cardNumber = $invoiceItem->getOrderItem()->getData('card_board_number');

            if ($qty<=0){
                throw new \Exception('The Qty is not available');
            }
            if ($cardNumber < 1 || $cardNumber > 54 || in_array($cardNumber, $this->prizeManagement->getDrawnCards($product->getId()))){
                throw new \Exception('The Card Number is not available');
            }
            if (! in_array($product->getQohStatus(), [\Angel\QoH\Model\Product\Attribute\Source\Status::PROCESSING, \Angel\QoH\Model\Product\Attribute\Source\Status::WAITING])){
                throw new \Exception('The Raffle is not saleable');
            }

            $customerId = $invoiceItem->getOrderItem()->getOrder()->getCustomerId();

            if (!$this->customerManagement->isInPurchaseGroup($customerId)){
                throw new \Exception('You are not a member');
            }

            /** @var Ticket $lastTicket */
            $lastTicket = $this->ticketManagement->getLastTicket($product->getId());
            $lastTicketNumber = $lastTicket->getEnd();

            $price = $product->getPrice() * $qty;

            $freeTickets = new DataObject(['free_ticket' => 0]);
            $this->eventManager->dispatch('angel_get_free_ticket', ['product' => $product, 'qty' => $qty, 'free' => $freeTickets]);
            $freeTickets = $freeTickets->getData('free_ticket');

            $fee = new DataObject(['extra_fee' => 0]);
            $this->eventManager->dispatch('angel_get_extra_fee', ['product' => $product, 'qty' => $qty, 'fee' => $fee]);
            $fee = $fee->getData('extra_fee');

            $this->ticketDataModel->setStart($lastTicketNumber + 1)
                ->setEnd($lastTicketNumber + $qty + $freeTickets)
                ->setPrice($price + $fee)
                ->setCustomerId($customerId)
                ->setProductId($product->getId())
                ->setCardNumber($cardNumber)
                ->setStatus(Status::STATUS_PAID)
                ->setSerial($this->generateSerial())
                ->setInvoiceItemId($invoiceItem->getId());
            $ticketData = $this->ticketRepository->save($this->ticketDataModel);

            $this->emailService->sendNewTicketEmail($product, $this->ticketDataModel);

            $this->ticket->getResource()->commit();
            if (!$freeTickets){
                $this->messageManager->addSuccessMessage(__('You purchased successfully %1 ticket(s)', $qty));
            } else {
                $this->messageManager->addSuccessMessage(__('You purchased successfully %1 tickets and get %2 free ticket(s)', $qty, $freeTickets));
            }
            return $ticketData;
        } catch (\Exception $e){
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->ticket->getResource()->rollBack();
        }
        return $this->ticketDataModel;
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
