<?php


namespace Angel\QoH\Controller\Adminhtml\Ticket;

use Angel\QoH\Model\PurchaseManagement;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductRepository;

class ImportPost extends \Magento\Backend\App\Action
{

    protected $dataPersistor;
    private $purchaseManagement;
    private $productRepository;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        PurchaseManagement $purchaseManagement,
        ProductRepository $productRepository
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->purchaseManagement = $purchaseManagement;
        $this->productRepository = $productRepository;
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

        $files = $this->getRequest()->getFiles('filecsv');
        if (isset($files)) {
            if (substr($files["name"], -4) != '.csv') {
                $this->messageManager->addError(__('Please choose a CSV file'));
                return $resultRedirect->setPath('*/*/import');
            }

            $fileName = $files['tmp_name'];
            $csvObject = $this->_objectManager->create('Magento\Framework\File\Csv');
            $data = $csvObject->getData($fileName);
            $product_id = $this->getRequest()->getParam('product_id');
            try {
                /** @var Product $product */
                $product = $this->productRepository->getById($product_id);
                $product->getResource()->beginTransaction();

                $this->purchaseManagement->importTicket($product, $data);
                $product->getResource()->commit();
                $this->messageManager->addSuccessMessage(__('Imported %1 ticket successfully.', count($data)-1));
                return $resultRedirect->setPath('*/*/index');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $product->getResource()->rollBack();
                return $resultRedirect->setPath('*/*/import');
            }

        }
    }
}
