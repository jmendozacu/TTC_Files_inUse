<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Block_Adminhtml_Quotes_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'quotes_form',
            array('legend' => Mage::helper('tgc_cms')->__('Quote Item Information'))
        );

        $fieldset->addField('is_active', 'select', array(
            'label'     => Mage::helper('tgc_cms')->__('Is Active'),
            'name'      => 'is_active',
            'values'    => array('0' => 'No', '1' => 'Yes'),
            'value'     => '1',
        ));

        $fieldset->addField('quote', 'textarea', array(
            'label'     => Mage::helper('tgc_cms')->__('Quote'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'quote',
        ));

        $fieldset->addField('source', 'text', array(
            'label'     => Mage::helper('tgc_cms')->__('Source'),
            'required'  => false,
            'name'      => 'source',
        ));

        $fieldset->addField('additional', 'text', array(
            'label'     => Mage::helper('tgc_cms')->__('Additional'),
            'required'  => false,
            'name'      => 'additional',
            'after_element_html' => '<p><small>This field will be displayed under the source</small></p>',
        ));

        $fieldset->addField('sort_order', 'text', array(
            'label'     => Mage::helper('tgc_cms')->__('Sort Order'),
            'class'     => 'validate-int required-entry',
            'required'  => true,
            'name'      => 'sort_order',
        ));

        $fieldset->addField('store', 'select', array(
            'label'     => Mage::helper('tgc_cms')->__('Store'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'store',
            'values'    => Mage::getModel('tgc_cms/source_store')->toOptionArray(),
            'value'     => '0',
        ));

        $data = array();
        if (Mage::getSingleton('adminhtml/session')->getQuotesFormData()) {
            $data = Mage::getSingleton('adminhtml/session')->getQuotesFormData();
            Mage::getSingleton('adminhtml/session')->setQuotesFormData(null);
        } elseif (Mage::registry('quotes_data')) {
            $data = Mage::registry('quotes_data')->getData();
        }

        $form->setValues($data);

        return parent::_prepareForm();
    }
}
