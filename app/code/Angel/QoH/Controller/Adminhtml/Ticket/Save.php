<?php


namespace Angel\QoH\Controller\Adminhtml\Ticket;

use Angel\QoH\Model\PurchaseManagement;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;
    private $customerRepository;
    private $purchaseManagement;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        CustomerRepository $customerRepository,
        PurchaseManagement $purchaseManagement
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->customerRepository = $customerRepository;
        $this->purchaseManagement = $purchaseManagement;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('ticket_id');
        
            $model = $this->_objectManager->create(\Angel\QoH\Model\Ticket::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Ticket no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            if (!$id){
                $email = $this->getRequest()->getParam('customer_email');
                $product_id = $this->getRequest()->getParam('product_id');
                $qty = $this->getRequest()->getParam('qty');
                $card_number = $this->getRequest()->getParam('card_number');
                $customer = $this->customerRepository->get($email);

                try {
                    $this->purchaseManagement->postPurchase($product_id, $qty, $card_number, $customer->getId());

                    $this->messageManager->addSuccessMessage(__('You saved the Ticket.'));

                    $this->dataPersistor->clear('angel_qoh_ticket');
                    if ($this->getRequest()->getParam('back')) {
                        return $resultRedirect->setPath('*/*/edit', ['ticket_id' => $model->getId()]);
                    }
                    return $resultRedirect->setPath('*/*/');
                } catch (\Exception $e){
                    $this->messageManager->addErrorMessage($e->getMessage());
                }

            } else {
                $model->setData($data);
                try {
                    $model->save();
                    $this->messageManager->addSuccessMessage(__('You saved the Ticket.'));
                    $this->dataPersistor->clear('angel_qoh_ticket');

                    if ($this->getRequest()->getParam('back')) {
                        return $resultRedirect->setPath('*/*/edit', ['ticket_id' => $model->getId()]);
                    }
                    return $resultRedirect->setPath('*/*/');
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                } catch (\Exception $e) {
                    $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Ticket.'));
                }

                $this->dataPersistor->set('angel_qoh_ticket', $data);
            }
            return $resultRedirect->setPath('*/*/edit', ['ticket_id' => $this->getRequest()->getParam('ticket_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
