<?php
/**
 * Copyright © 2016 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Created by PhpStorm.
 * User: steve
 * Date: 07/09/2016
 * Time: 22:26
 */

namespace Angel\QoH\Service;

use Angel\QoH\Model\Card\Options;
use Angel\QoH\Model\Prize;
use Angel\QoH\Model\Ticket;
use Magento\Catalog\Model\Product;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class Email
{
    const EMAIL_TEMPLATE_WINNING = "angel_qoh_winning";
    const EMAIL_TEMPLATE_FINISHED = "angel_qoh_losing";
    const EMAIL_TEMPLATE_NEW_TICKET = "angel_qoh_purchase_ticket";

    /** @var array of name and email of the sender ['name'=>'sender_name', 'email'=>'steve@magetore.com']  */
    protected $_sender;

    /** @var  array of receiver emails: ['receiver1@magestore.com', 'receiver2@gmail.com'] */
    protected $_receivers;

    /** @var   */
    protected $_templateVars;

    /** @var   */
    protected $_emailTemplate;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        PriceCurrencyInterface $priceCurrency,
        CustomerRepositoryInterface $customerRepository
    ){
        $this->scopeConfig = $scopeConfig;
        $this->transportBuilder = $transportBuilder;
        $this->customerRepository = $customerRepository;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @return array
     */
    public function getSender(){
        if(!$this->_sender){
            $sender = [
                'name' => $this->scopeConfig->getValue('trans_email/ident_general/name'),
                'email' => $this->scopeConfig->getValue('trans_email/ident_general/email'),
            ];
            $this->_sender = $sender;
        }
        return $this->_sender;
    }

    /**
     * @param $sender
     */
    public function setSender($sender){
        $this->_sender = $sender;
    }

    /**
     * @return array
     */
    public function getReceivers(){
        return $this->_receivers;
    }

    /**
     * @param $receiver
     */
    public function setReceivers($receivers){
        $this->_receivers = $receivers;
    }

    /**
     * @param $templateVars
     */
    public function setTemplateVars($templateVars){
        $this->_templateVars = $templateVars;
    }

    /**
     * @return mixed
     */
    public function getTemplateVars(){
        return $this->_templateVars;
    }

    /**
     * @return mixed
     */
    public function getEmailTemplate(){
        return $this->_emailTemplate;
    }

    /**
     * @param $emailTemplate
     */
    public function setEmailTemplate($emailTemplate){
        $this->_emailTemplate = $emailTemplate;
    }

    /**
     *
     */
    public function sendEmail(){
        $notifierEmails = explode(',', $this->getReceivers());
        foreach ($notifierEmails as $email) {
            try {
                if($email){
                    $transport = $this->transportBuilder
                        ->setTemplateIdentifier($this->getEmailTemplate())
                        ->setTemplateOptions(
                            [
                                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                                'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                            ]
                        )
                        ->setTemplateVars($this->getTemplateVars())
                        ->setFrom($this->getSender())
                        ->addTo(trim($email))
                        ->getTransport();
                    $transport->sendMessage();
                }
            } catch (\Exception $e) {
                return;
            }
        }
    }


    /**
     * @param Product $product
     * @param Prize|\Angel\QoH\Model\Data\Prize $prize
     * @param Ticket|\Angel\QoH\Model\Data\Ticket $ticket
     * @return string
     */
    public function sendWinningEmail($product, $prize, $ticket){
        try {
            $customer = $this->customerRepository->getById($ticket->getCustomerId());
            $this->setReceivers($customer->getEmail());
            $this->setEmailTemplate(self::EMAIL_TEMPLATE_NEW_TICKET);
            $templateVars = [
                'customer' => $customer,
                'product_name' => $product->getName(),
                'winning_number' => $prize->getWinningNumber(),
                'card_label' => Options::getCardLabel($prize->getCard()),
                'winning_prize' => $this->priceCurrency->format($prize->getPrize()),
                'start' => $ticket->getStart(),
                'end' => $ticket->getEnd(),
                'price' => $this->priceCurrency->format($ticket->getPrice(), false, 0)
            ];
            $this->setTemplateVars($templateVars);
            //send email
            $this->sendEmail();
        } catch (\Exception $e){

        }
        return $ticket->getCustomerEmail();
    }

    /**
     * @param Product $product
     * @param Prize|\Angel\QoH\Model\Data\Prize $prize
     * @param Ticket|\Angel\QoH\Model\Data\Ticket $ticket
     * @param string $email
     */
    public function sendFinishedEmail($product, $prize, $ticket){
        try {
            $customer = $this->customerRepository->getById($ticket->getCustomerId());
            $this->setReceivers($customer->getEmail());
            $this->setEmailTemplate(self::EMAIL_TEMPLATE_NEW_TICKET);
            $templateVars = [
                'customer' => $customer,
                'product_name' => $product->getName(),
                'winning_number' => $prize->getWinningNumber(),
                'card_label' => Options::getCardLabel($prize->getCard()),
                'winning_prize' => $this->priceCurrency->format($prize->getPrize(), false, 0)
            ];
            $this->setTemplateVars($templateVars);
            //send email
            $this->sendEmail();
        } catch (\Exception $e){

        }
    }

    /**
     * @param Product $product
     * @param Ticket|\Angel\QoH\Model\Data\Ticket $ticket
     */
    public function sendNewTicketEmail($product, $ticket){
        try {
            $customer = $this->customerRepository->getById($ticket->getCustomerId());
            $this->setReceivers($customer->getEmail());
            $this->setEmailTemplate(self::EMAIL_TEMPLATE_NEW_TICKET);
            $templateVars = [
                'customer' => $customer,
                'product_name' => $product->getName(),
                'start' => $ticket->getStart(),
                'end' => $ticket->getEnd(),
                'only' => $ticket->getStart() == $ticket->getEnd(),
                'board_number' => $ticket->getCardNumber(),
                'price' => $this->priceCurrency->format($ticket->getPrice(), false, 0)
            ];
            $this->setTemplateVars($templateVars);
            //send email
            $this->sendEmail();
        } catch (\Exception $e){

        }
    }
}