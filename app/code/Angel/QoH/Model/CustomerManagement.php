<?php


namespace Angel\QoH\Model;


use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Framework\Message\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;

class CustomerManagement
{
    private $storeManager;
    private $accountManagement;
    private $customerFactory;
    private $customerRepository;
    private $messageManager;

    public function __construct(
        StoreManagerInterface $storeManager,
        AccountManagementInterface $accountManagement,
        CustomerFactory $customerFactory,
        CustomerRepository $customerRepository,
        ManagerInterface $mesageManager
    ){
        $this->storeManager = $storeManager;
        $this->accountManagement = $accountManagement;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->messageManager = $mesageManager;
    }

    /**
     * @param $email
     * @return \Magento\Customer\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     */
    public function getOrCreateCustomerByEmail($email){
        /** @var Customer $customer */
        $customer = $this->customerFactory->create();
        $websiteId = $this->storeManager->getStore()->getWebsiteId();

        if (isset($websiteId)) {
            $customer->setWebsiteId($websiteId);
        }
        $email = trim($email);
        $customer->loadByEmail($email);
        if (!$customer->getEmail()) {
            $customer   = $this->customerFactory->create();
            $customer->setWebsiteId($websiteId);

            // Preparing data for new customer
            $customer->setEmail($email);
            $customer->setFirstname("player");
            $customer->setLastname("player");

            $customer = $this->accountManagement->createAccount($customer->getDataModel(), $this->generateRandomString());
            $this->messageManager->addSuccessMessage(__('Created an new account with email %1', $email));
            return $customer;
        } else {
            return $customer->getDataModel();
        }
    }

    private function generateRandomString($length = 15) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
