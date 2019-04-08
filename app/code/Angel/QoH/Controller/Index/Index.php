<?php


namespace Angel\QoH\Controller\Index;

use Angel\QoH\Model\QohManagement;

class Index extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    private $qohManagement;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        QohManagement $qohManagement
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->qohManagement = $qohManagement;
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->qohManagement->massUpdateStatus();
        $page = $this->resultPageFactory->create();
        $page->getConfig()->addBodyClass('page-products');
        $page->getConfig()->getTitle()->prepend(__('50-50 Processing Raffle Products'));
        return $page;
    }
}
