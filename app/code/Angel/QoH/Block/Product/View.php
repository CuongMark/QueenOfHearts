<?php


namespace Angel\QoH\Block\Product;

use Angel\QoH\Model\QohManagement;
use Magento\Catalog\Api\ProductRepositoryInterface;

class View extends \Magento\Catalog\Block\Product\View
{
    private $qohManagement;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        QohManagement $qohManagement,
        array $data = []
    ){
        parent::__construct($context, $urlEncoder, $jsonEncoder, $string, $productHelper, $productTypeConfig, $localeFormat, $customerSession, $productRepository, $priceCurrency, $data);
        $this->qohManagement = $qohManagement;
    }

    public function getJackPot(){
        return $this->qohManagement->getJackPot($this->getProduct());
    }

    public function getJackpotFormated(){
        return $this->priceCurrency->format($this->qohManagement->getJackPot($this->getProduct()));
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
}
