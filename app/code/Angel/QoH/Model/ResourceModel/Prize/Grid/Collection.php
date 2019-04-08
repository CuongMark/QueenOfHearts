<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Angel\QoH\Model\ResourceModel\Prize\Grid;

use Angel\QoH\Model\PrizeManagement;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;

/**
 * Order grid collection
 */
class Collection extends \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
{
    /**
     * @var RequestInterface
     */
    private $request;
    private $ticketManagement;

    /**
     * Collection constructor.
     * @param EntityFactory $entityFactory
     * @param Logger $logger
     * @param FetchStrategy $fetchStrategy
     * @param EventManager $eventManager
     * @param RequestInterface $request
     * @param PrizeManagement $ticketManagement
     * @param string $mainTable
     * @param string $resourceModel
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        RequestInterface $request,
        PrizeManagement $ticketManagement,
        $mainTable = 'angel_qoh_prize',
        $resourceModel = \Angel\QoH\Model\ResourceModel\Prize::class
    ) {
        $this->request = $request;
        $this->ticketManagement = $ticketManagement;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel);
    }

    /**
     * Initialize select
     *
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->_addFilters();
        return $this;
    }

    protected function _joinFields()
    {
    }

    protected function _addFilters()
    {
        if ($this->request->getParam('current_product_id'))
            $this->addFieldToFilter('main_table.product_id', $this->request->getParam('current_product_id'));
    }

}
