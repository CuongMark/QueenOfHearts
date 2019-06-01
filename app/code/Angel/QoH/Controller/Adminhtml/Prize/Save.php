<?php


namespace Angel\QoH\Controller\Adminhtml\Prize;

use Angel\QoH\Model\Prize;
use Angel\QoH\Model\Prize\Status;
use Angel\QoH\Model\TicketManagement;
use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Gateway\Http\Client\Zend;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;
    private $ticketManagement;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        TicketManagement $ticketManagement
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
        $this->ticketManagement = $ticketManagement;
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
            $id = $this->getRequest()->getParam('prize_id');

            /** @var \Angel\QoH\Model\Data\Prize|Prize $model */
            $model = $this->_objectManager->create(\Angel\QoH\Model\Prize::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Prize no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            // disable edit if auto draw
            if ($model->getAutoDraw() && $data['status'] != Status::STATUS_PENDING){
                $model->setStatus($data['status']);
                $this->messageManager->addNoticeMessage(__('This prize is auto draw winning number. So you only able to change the status.'));
            } else {
                $model->setData($data);
            }
        
            try {
                if ($model->getStatus() == Status::STATUS_PROCESSING || $model->getStatus() == Status::STATUS_PAID){
                    if ($model->getAutoDraw()) {
                        $this->ticketManagement->winningTickets($model);
                    }
                } elseif ($model->getStatus() == Status::STATUS_CANCELED) {
                    $this->ticketManagement->cancelTickets($model);
                }

                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Prize.'));
                $this->dataPersistor->clear('angel_qoh_prize');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['prize_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
//                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Prize.'));
            }
        
            $this->dataPersistor->set('angel_qoh_prize', $data);
            return $resultRedirect->setPath('*/*/edit', ['prize_id' => $this->getRequest()->getParam('prize_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
