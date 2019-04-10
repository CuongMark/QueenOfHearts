<?php


namespace Angel\QoH\Controller\Adminhtml\Ticket;

use Magento\Framework\App\Filesystem\DirectoryList;

class SampleFile extends \Magento\Backend\App\Action
{
    /**
     * Execute action
     */
    public function execute()
    {
        $fileName = 'ticket.csv';

        /** @var \Magento\Framework\App\Response\Http\FileFactory $fileFactory */
        $fileFactory = $this->_objectManager->get('Magento\Framework\App\Response\Http\FileFactory');

        return $fileFactory->create(
            $fileName,
            $this->getTicketSampleData(),
            DirectoryList::VAR_DIR
        );
    }

    public function getTicketSampleData()
    {
        /** @var \Magento\Framework\Module\Dir $moduleReader */
        $moduleReader = $this->_objectManager->get('Magento\Framework\Module\Dir');
        /** @var \Magento\Framework\Filesystem\DriverPool $drivePool */
        $drivePool = $this->_objectManager->get('Magento\Framework\Filesystem\DriverPool');
        $drive = $drivePool->getDriver(\Magento\Framework\Filesystem\DriverPool::FILE);

        return $drive->fileGetContents($moduleReader->getDir('Angel_QoH')
            . DIRECTORY_SEPARATOR . '_fixtures' . DIRECTORY_SEPARATOR . 'ticket.csv');
    }
}
