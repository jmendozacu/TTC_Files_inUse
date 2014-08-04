<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Block_Adminhtml_Partners_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'partners_form',
            array('legend' => Mage::helper('tgc_cms')->__('Partner Item Information'))
        );

        $fieldset->addField('is_active', 'select', array(
            'label' => Mage::helper('tgc_cms')->__('Is Active'),
            'name' => 'is_active',
            'values' => array('0' => 'No', '1' => 'Yes'),
            'value' => '1',
        ));

        $fieldset->addField('description', 'text', array(
            'label' => Mage::helper('tgc_cms')->__('Description'),
            'required' => false,
            'name' => 'description',
            'after_element_html' => '<p><small>This field is used as the image title</small></p>',
        ));

        $fieldset->addField('sort_order', 'text', array(
            'label' => Mage::helper('tgc_cms')->__('Sort Order'),
            'class' => 'validate-int',
            'required' => false,
            'name' => 'sort_order',
        ));

        $fieldset->addField('alt_text', 'text', array(
            'label' => Mage::helper('tgc_cms')->__('Alt Text'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'alt_text',
            'after_element_html' => '<p><small>This field is used as the image alt text</small></p>',
        ));

        $fieldset->addField('image', 'image', array(
            'label' => Mage::helper('tgc_cms')->__('Image'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'image'
        ));

        $fieldset->addField('store', 'select', array(
            'label' => Mage::helper('tgc_cms')->__('Store'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'store',
            'values' => Mage::getModel('tgc_cms/source_store')->toOptionArray(),
            'value' => '0',
        ));

        $fieldset->addField('url', 'text', array(
            'label' => Mage::helper('tgc_cms')->__('URL'),
            'name' => 'url'
        ));

        $data = array();
        if (Mage::getSingleton('adminhtml/session')->getPartnersFormData()) {
            $data = Mage::getSingleton('adminhtml/session')->getPartnersFormData();
            Mage::getSingleton('adminhtml/session')->setPartnersFormData(null);
        } elseif (Mage::registry('partners_data')) {
            $data = Mage::registry('partners_data')->getData();
        }

        $form->setValues($data);

        return parent::_prepareForm();
    }
}
