<?php


namespace Angel\QoH\Block;

use Angel\QoH\Model\Product\Attribute\Source\Status;
use Angel\QoH\Model\QohManagement;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Ui\DataProvider\Product\ProductCollectionFactory;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class CardBoard extends \Magento\Framework\View\Element\Template
{
    private $qohManagement;
    private $productRepository;
    private $productCollectionFactory;
    protected $product;
    private $priceCurrency;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        QohManagement $qohManagement,
        ProductRepository $productRepository,
        ProductCollectionFactory $productCollectionFactory,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        $this->qohManagement = $qohManagement;
        $this->productRepository = $productRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->priceCurrency = $priceCurrency;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setTemplate('Angel_QoH::card_board.phtml');
        return $this;
    }

    public function getJackPot(){
        return $this->qohManagement->getJackPot($this->getProduct());
    }

    /**
     * @return array
     */
    public function getPrizes(){
        $fields = ['prize_id', 'card', 'card_number', 'prize', 'winning_number'];
        $prizes = $this->qohManagement->getPrizes($this->getProduct());
        $prizeData = [];
        foreach ($prizes as $prize){
            foreach ($fields as $field){
                $data[$field] = $prize->getData($field);
            }
            $prizeData[] = $data;
        }
        return $prizeData;
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductInterface|\Magento\Framework\DataObject|mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProduct(){
        if (!$this->product) {
            try {
                $this->product = $this->productRepository->getById($this->getProductId());
            } catch (\Exception $e) {
                $collection = $this->productCollectionFactory->create();
                $collection->addAttributeToSelect('*');
                $collection->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
                $collection->addAttributeToFilter('status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
                $collection->addFieldToFilter('type_id', \Angel\QoH\Model\Product\Type\Qoh::TYPE_ID);
                $collection->addAttributeToFilter('qoh_status', ['in' => [Status::PROCESSING, Status::WAITING]]);
                $collection->addStoreFilter($this->_storeManager->getStore()->getId());
                $collection->setPageSize(1)->setCurPage(1);
                $this->product = $collection->getFirstItem();
            }
        }
        return $this->product;
    }

    /**
     * Retrieve formated price
     *
     * @param float $value
     * @return string
     */
    public function formatPrice($value, $isHtml = true)
    {
        return $this->priceCurrency->format(
            $value,
            $isHtml,
            PriceCurrencyInterface::DEFAULT_PRECISION,
            1 //Todo getStore
        );
    }
}
