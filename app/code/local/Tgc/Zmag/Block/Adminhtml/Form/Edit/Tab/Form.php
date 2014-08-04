<?php
/**
 * User: mhidalgo
 * Date: 11/03/14
 * Time: 16:55
 */
class Tgc_Zmag_Block_Adminhtml_Form_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        if (Mage::getSingleton('adminhtml/session')->getZmagData()) {
            $data = Mage::getSingleton('adminhtml/session')->getZmagData();
            Mage::getSingleton('adminhtml/session')->getZmagData(null);
        } elseif (Mage::registry('zmag_data')) {
            $data = Mage::registry('zmag_data')->getData();
        } else {
            $data = array();
        }

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('form_form', array('legend' => Mage::helper('tgc_zmag')->__('Zmag information')));

        $fieldset->addField('publication_id', 'text', array(
            'label' => Mage::helper('tgc_zmag')->__('Publication ID'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'publication_id',
        ));

        $fieldset->addField('page_instructions', 'textarea', array(
            'label' => Mage::helper('tgc_zmag')->__('Page Instructions'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'page_instructions',
        ));

        $fieldset->addField('icon', 'image', array(
            'label' => Mage::helper('tgc_zmag')->__('Icon'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'icon',
        ));

        $fieldset->addField('website_id', 'select', array(
            'label' => Mage::helper('tgc_zmag')->__('Website'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'website_id',
            'values' => Mage::helper('tgc_zmag')->getWebsiteOptions(),
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('tgc_zmag')->__('Status'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'status',
            'values' => array(Tgc_Zmag_Model_Zmag::STATUS_ENABLED => 'Enabled', Tgc_Zmag_Model_Zmag::STATUS_DISABLED => 'Disabled'),
        ));

        $groups = Mage::getResourceModel('customer/group_collection')
            ->addFieldToFilter('customer_group_id', array('gt'=> 0))
            ->load()
            ->toOptionHash();

        $fieldset->addField('customer_type', 'select', array(
            'label' => Mage::helper('customer')->__('Group'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'customer_type',
            'values' => $groups,
        ));

        $form->setValues($data);

        return parent::_prepareForm();
    }
}