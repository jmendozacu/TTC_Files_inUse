<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_Adminhtml_MergeAccounts_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'mergeAccounts_form',
            array('legend' => Mage::helper('tgc_dl')->__('Merged Account Information'))
        );

        $fieldset->addField('dax_customer_id', 'text', array(
            'label'     => Mage::helper('tgc_dl')->__('DAX Customer ID'),
            'required'  => true,
            'name'      => 'dax_customer_id',
        ));

        $fieldset->addField('mergeto_dax_customer_id', 'text', array(
            'label'     => Mage::helper('tgc_dl')->__('Merge To DAX Customer ID'),
            'required'  => true,
            'name'      => 'mergeto_dax_customer_id',
        ));

        if (Mage::getSingleton('adminhtml/session')->getMergeAccountsData())
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getMergeAccountsData());
            Mage::getSingleton('adminhtml/session')->setMergeAccountsData(null);
        } elseif (Mage::registry('mergeAccounts_data')) {
            $form->setValues(Mage::registry('mergeAccounts_data')->getData());
        }

        return parent::_prepareForm();
    }
}
