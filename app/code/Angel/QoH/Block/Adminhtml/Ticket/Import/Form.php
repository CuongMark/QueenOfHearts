<?php

/**
 * Magestore.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Storepickup
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

namespace Angel\QoH\Block\Adminhtml\Ticket\Import;

use Angel\QoH\Model\Product\SelectProducts;
use Magento\Backend\Block\Widget\Form\Generic;

/**
 * Class Tab GeneralTab
 */
class Form extends Generic
{
    private $selectProducts;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        SelectProducts $selectProducts,
        array $data = []
    ){
        $this->selectProducts = $selectProducts;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id'      => 'edit_form',
                    'action'  => $this->getUrl('*/*/importPost'),
                    'method'  => 'post',
                    'enctype' => 'multipart/form-data',
                ],
            ]
        );

        $fieldset = $form->addFieldset('general_fieldset', ['legend' => __('Import Information')]);

        $fieldset->addField(
            'product_id',
            'select',
            [
                'title'    => __('Queen of Hearts Product'),
                'label'    => __('Queen of Hearts Product'),
                'name'     => 'product_id',
                'required' => true,
                'options'  => $this->selectProducts->getOptionArray(),
                'note'     => ''
            ]
        );

        $fieldset->addField(
            'filecsv',
            'file',
            [
                'title'    => __('Import File'),
                'label'    => __('Import File'),
                'name'     => 'filecsv',
                'required' => true,
                'note'     => 'Only csv file is supported. Click <a target="_blank" href="'
                    . $this->getUrl('*/*/sampleFile')
                    . '">here</a> to download the Sample CSV file',
            ]
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
