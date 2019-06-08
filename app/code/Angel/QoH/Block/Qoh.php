<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 4/16/2019
 * Time: 12:10 AM
 */

namespace Angel\QoH\Block;


use Angel\QoH\Model\Card\Options;
use Angel\QoH\Model\Prize\Status;
use Angel\QoH\Model\QohManagement;
use Angel\QoH\Model\ResourceModel\Prize\Collection;
use Angel\QoH\Model\ResourceModel\Prize\CollectionFactory;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template;

class Qoh extends \Magento\Framework\View\Element\Template
{

    private $prizeCollectionFactory;
    private $qohManagement;
    private $priceCurrency;

    public function __construct(
        Template\Context $context,
        CollectionFactory $prizeCollectionFactory,
        QohManagement $qohManagement,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ){
        $this->prizeCollectionFactory = $prizeCollectionFactory;
        $this->qohManagement = $qohManagement;
        $this->priceCurrency = $priceCurrency;
        parent::__construct($context, $data);
    }

    /**
     * @return Collection|bool
     */
    public function getPrizes(){
        try {
            /** @var Collection $prizes */
            $prizes = $this->prizeCollectionFactory->create();
            $prizes->addFieldToFilter('status', ['in' => [Status::STATUS_PROCESSING, Status::STATUS_PAID]])
                ->setCurPage(1)
                ->setPageSize(5)
                ->setOrder('prize_id', 'DESC');
            $this->qohManagement->joinProductName($prizes);
        } catch (\Exception $e){
            return false;
        }
        return $prizes;
    }

    public function getImageSrc($card){
        $cards = Options::getCardSrc();
        return $this->getUrl().Options::IMG_PATH.'/'. $cards[$card - 1];
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