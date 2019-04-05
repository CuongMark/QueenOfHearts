<?php

namespace Angel\QoH\Block\Adminhtml\Product;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class AddNewButton extends GenericButton implements ButtonProviderInterface
{

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    public function __construct(
        Context $context,
        \Magento\Catalog\Model\ProductFactory $productFactory
    ){
        $this->_productFactory = $productFactory;
        parent::__construct($context);
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Add New Queen of Hearts Raffle'),
            'on_click' => sprintf("location.href = '%s';", $this->_getProductCreateUrl(\Angel\QoH\Model\Product\Type\Qoh::TYPE_ID)),
            'class' => 'primary',
            'id' => 'add',
            'sort_order' => 10
        ];
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/');
    }

    /**
     * Retrieve product create url by specified product type
     *
     * @param string $type
     * @return string
     */
    protected function _getProductCreateUrl($type)
    {
        return $this->getUrl(
            'catalog/product/new',
            ['set' => $this->_productFactory->create()->getDefaultAttributeSetId(), 'type' => $type]
        );
    }
}
