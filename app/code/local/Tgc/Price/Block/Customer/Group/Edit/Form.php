<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
 class Tgc_Price_Block_Customer_Group_Edit_Form extends Mage_Adminhtml_Block_Customer_Group_Edit_Form 
 {
     /**
      * Adds two read-only fields to form: catalog code and ad code
      * 
      * @see Mage_Adminhtml_Block_Customer_Group_Edit_Form::_prepareLayout()
      */
    protected function _prepareLayout()
    {    
        parent::_prepareLayout();
        
        $fieldset = $this->getForm()->getElement('base_fieldset');
        $customerGroup = Mage::registry('current_group');
        
        $fieldset->addField('catalog_code', 'label', array(
            'name'  => 'catalog_code',
            'label' => $this->__('Catalog Code'),
            'note'  => $this->__('Catalog code from DAX.'),
            'value' => $customerGroup->getCatalogCode(),
        ));
        
        $fieldset->addField('ad_codes', 'label', array(
            'name'  => 'catalog_code',
            'label' => $this->__('Ad Codes'),
            'note'  => $this->__('Ad codes from DAX.'),
            'value' => implode(', ', $customerGroup->getAdCodes()),
        ));
    }
 }