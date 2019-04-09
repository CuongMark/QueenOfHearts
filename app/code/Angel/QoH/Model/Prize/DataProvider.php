<?php


namespace Angel\QoH\Model\Prize;

use Angel\QoH\Model\TicketRepository;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Angel\QoH\Model\ResourceModel\Prize\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    protected $dataPersistor;

    protected $collection;

    protected $loadedData;
    private $productRepository;
    private $customerRepository;
    private $ticketRepository;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        ProductRepository $productRepository,
        CustomerRepositoryInterface $customerRepository,
        TicketRepository $ticketRepository,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->productRepository = $productRepository;
        $this->customerRepository = $customerRepository;
        $this->ticketRepository = $ticketRepository;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $product = $this->productRepository->getById($model->getProductId());
            $model->setProductName($product->getName());

            if ($model->getTicketId()){
                $ticket = $this->ticketRepository->getById($model->getTicketId());
                $customer = $this->customerRepository->getById($ticket->getCustomerId());
                $model->setCustomerEmail($customer->getEmail());
            }
            $this->loadedData[$model->getId()] = $model->getData();
        }
        $data = $this->dataPersistor->get('angel_qoh_prize');
        
        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('angel_qoh_prize');
        }
        
        return $this->loadedData;
    }
}
