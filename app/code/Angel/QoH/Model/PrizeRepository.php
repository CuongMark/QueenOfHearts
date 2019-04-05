<?php


namespace Angel\QoH\Model;

use Magento\Framework\Reflection\DataObjectProcessor;
use Angel\QoH\Api\PrizeRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Angel\QoH\Api\Data\PrizeInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Angel\QoH\Api\Data\PrizeSearchResultsInterfaceFactory;
use Angel\QoH\Model\ResourceModel\Prize\CollectionFactory as PrizeCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Angel\QoH\Model\ResourceModel\Prize as ResourcePrize;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

class PrizeRepository implements PrizeRepositoryInterface
{

    protected $dataPrizeFactory;

    protected $prizeFactory;

    protected $dataObjectHelper;

    private $collectionProcessor;

    protected $dataObjectProcessor;

    protected $prizeCollectionFactory;

    protected $resource;

    protected $extensibleDataObjectConverter;
    protected $searchResultsFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;


    /**
     * @param ResourcePrize $resource
     * @param PrizeFactory $prizeFactory
     * @param PrizeInterfaceFactory $dataPrizeFactory
     * @param PrizeCollectionFactory $prizeCollectionFactory
     * @param PrizeSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourcePrize $resource,
        PrizeFactory $prizeFactory,
        PrizeInterfaceFactory $dataPrizeFactory,
        PrizeCollectionFactory $prizeCollectionFactory,
        PrizeSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->prizeFactory = $prizeFactory;
        $this->prizeCollectionFactory = $prizeCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataPrizeFactory = $dataPrizeFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Angel\QoH\Api\Data\PrizeInterface $prize
    ) {
        /* if (empty($prize->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $prize->setStoreId($storeId);
        } */
        
        $prizeData = $this->extensibleDataObjectConverter->toNestedArray(
            $prize,
            [],
            \Angel\QoH\Api\Data\PrizeInterface::class
        );
        
        $prizeModel = $this->prizeFactory->create()->setData($prizeData);
        
        try {
            $this->resource->save($prizeModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the prize: %1',
                $exception->getMessage()
            ));
        }
        return $prizeModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($prizeId)
    {
        $prize = $this->prizeFactory->create();
        $this->resource->load($prize, $prizeId);
        if (!$prize->getId()) {
            throw new NoSuchEntityException(__('Prize with id "%1" does not exist.', $prizeId));
        }
        return $prize->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->prizeCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Angel\QoH\Api\Data\PrizeInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Angel\QoH\Api\Data\PrizeInterface $prize
    ) {
        try {
            $prizeModel = $this->prizeFactory->create();
            $this->resource->load($prizeModel, $prize->getPrizeId());
            $this->resource->delete($prizeModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Prize: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($prizeId)
    {
        return $this->delete($this->getById($prizeId));
    }
}
