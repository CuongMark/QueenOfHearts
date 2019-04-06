<?php


namespace Angel\QoH\Service;

use Angel\Auction\Model\Bid;
use Angel\Auction\Model\ResourceModel\Bid\Collection;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class Files
{
    /**
     * @var Filesystem
     */
    protected $fileSystem;
    /**
     * @var DirectoryList
     */
    protected $directoryList;
    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    public function __construct(
        Filesystem $filesystem,
        DirectoryList $directoryList,
        \Magento\Framework\Json\Helper\Data $jsonHelper
    ){
        $this->fileSystem = $filesystem;
        $this->directoryList = $directoryList;
        $this->jsonHelper = $jsonHelper;

    }

    public function createFile($data, $id){
        try {
            $data['id'] = mt_rand(1000,9999);
            $media = $this->fileSystem->getDirectoryWrite(DirectoryList::MEDIA);
            $media->writeFile('angel/qoh/qoh_'.$id.'.json', $this->jsonHelper->jsonEncode($data));
        } catch(\Exception $e){

        }
    }

}
