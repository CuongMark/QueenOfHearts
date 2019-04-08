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

class General extends AbstractModifier
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
        /** @var Product $product */
        $product = $this->locator->getProduct();
        if ($product->getTypeId() != \Angel\QoH\Model\Product\Type\Qoh::TYPE_ID){
            return $meta;
        }
        $meta = $this->enableTime($meta);
//        $meta = $this->disableStatusField($meta);
//        /** @var \Angel\QoH\Model\Product\Type\Qph $productTypeInstance */
//        $productTypeInstance = $product->getTypeInstance();
//        if ($product->getQoHStatus() != Status::NOT_START) {
////            $meta = $this->disableStartAtField($meta);
//            $meta = $this->disableStartPotField($meta);
//        }
//        if ($productTypeInstance->isFinished($product)) {
//            $meta = $this->disableFinishAtField($meta);
//        }

        return $meta;
    }

    protected function disableStatusField(array $meta){
        $meta = array_replace_recursive(
            $meta,
            [
                'product-details' => [
                    'children' => [
                        'container_fifty_status' => [
                            'children' => [
                                'fifty_status' =>[
                                    'arguments' => [
                                        'data' => [
                                            'config' => [
                                                'disabled' => true,
                                            ],
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
        return $meta;
    }

    protected function disableStartPotField(array $meta){
        $meta = array_replace_recursive(
            $meta,
            [
                'product-details' => [
                    'children' => [
                        'container_start_pot' => [
                            'children' => [
                                'start_pot' =>[
                                    'arguments' => [
                                        'data' => [
                                            'config' => [
                                                'disabled' => true,
                                            ],
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
        return $meta;
    }

    protected function disableStartAtField(array $meta){
        $meta = array_replace_recursive(
            $meta,
            [
                'product-details' => [
                    'children' => [
                        'container_fifty_start_at' => [
                            'children' => [
                                'fifty_start_at' =>[
                                    'arguments' => [
                                        'data' => [
                                            'config' => [
                                                'disabled' => true,
                                            ],
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
        return $meta;
    }

    protected function disableFinishAtField(array $meta){
        $winningNumber = $this->locator->getProduct()->getTypeInstance()->getPrize($this->locator->getProduct())->getWinningNumber();
        $winningPrize = $this->locator->getProduct()->getTypeInstance()->getPrize($this->locator->getProduct())->getWinningPrize();
        $notice = $winningNumber?__('Winning Number: %1. Winning Prize: %2', $winningNumber, $this->priceCurrency->format($winningPrize, false, 0)):'';
        $meta = array_replace_recursive(
            $meta,
            [
                'product-details' => [
                    'children' => [
                        'container_qoh_finish_at' => [
                            'children' => [
                                'qoh_finish_at' =>[
                                    'arguments' => [
                                        'data' => [
                                            'config' => [
                                                'disabled' => true,
                                                'notice' => $notice
                                            ],
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
        return $meta;
    }

    /**
     * Customise Custom Attribute field
     *
     * @param array $meta
     *
     * @return array
     */
    protected function enableTime(array $meta)
    {
        $fieldCode = 'qoh_start_at';
        $elementPath = $this->arrayManager->findPath($fieldCode, $meta, null, 'children');
        $containerPath = $this->arrayManager->findPath(static::CONTAINER_PREFIX . $fieldCode, $meta, null, 'children');
        if ($elementPath) {
            $meta = $this->arrayManager->merge(
                $containerPath,
                $meta,
                [
                    'children' => [
                        $fieldCode => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'default' => '',
                                        'options' => [
                                            'dateFormat' => 'Y-m-d',
                                            'timeFormat' => 'HH:mm:ss',
                                            'showsTime' => true
                                        ]
                                    ],
                                ],
                            ],
                        ]
                    ]
                ]
            );
        }

        $fieldCode = 'qoh_finish_at';
        $elementPath = $this->arrayManager->findPath($fieldCode, $meta, null, 'children');
        $containerPath = $this->arrayManager->findPath(static::CONTAINER_PREFIX . $fieldCode, $meta, null, 'children');
        if ($elementPath) {
            $meta = $this->arrayManager->merge(
                $containerPath,
                $meta,
                [
                    'children' => [
                        $fieldCode => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'default' => '',
                                        'options' => [
                                            'dateFormat' => 'Y-m-d',
                                            'timeFormat' => 'HH:mm:ss',
                                            'showsTime' => true
                                        ]
                                    ],
                                ],
                            ],
                        ]
                    ]
                ]
            );
        }
        return $meta;
    }
}
