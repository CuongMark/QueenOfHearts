<?php

/**
 * Copyright © 2016 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Angel\QoH\Ui\DataProvider\Product\Form\Modifier;

use Angel\Fifty\Model\Product\Attribute\Source\FiftyStatus;
use Angel\QoH\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\ArrayManager;

class Tickets extends AbstractModifier
{
    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * @var ArrayManager
     */
    protected $arrayManager;
    private $priceCurrency;

    public function __construct(
        LocatorInterface $locator,
        ArrayManager $arrayManager,
        PriceCurrencyInterface $priceCurrency
    ){
        $this->locator = $locator;
        $this->arrayManager = $arrayManager;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }

}
