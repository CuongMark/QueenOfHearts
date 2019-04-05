<?php


namespace Angel\QoH\Model;

use Magento\Framework\Api\DataObjectHelper;
use Angel\QoH\Api\Data\TicketInterface;
use Angel\QoH\Api\Data\TicketInterfaceFactory;

class Ticket extends \Magento\Framework\Model\AbstractModel
{

    protected $ticketDataFactory;

    protected $_eventPrefix = 'angel_qoh_ticket';
    protected $dataObjectHelper;


    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param TicketInterfaceFactory $ticketDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Angel\QoH\Model\ResourceModel\Ticket $resource
     * @param \Angel\QoH\Model\ResourceModel\Ticket\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        TicketInterfaceFactory $ticketDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Angel\QoH\Model\ResourceModel\Ticket $resource,
        \Angel\QoH\Model\ResourceModel\Ticket\Collection $resourceCollection,
        array $data = []
    ) {
        $this->ticketDataFactory = $ticketDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve ticket model with ticket data
     * @return TicketInterface
     */
    public function getDataModel()
    {
        $ticketData = $this->getData();
        
        $ticketDataObject = $this->ticketDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $ticketDataObject,
            $ticketData,
            TicketInterface::class
        );
        
        return $ticketDataObject;
    }
}
