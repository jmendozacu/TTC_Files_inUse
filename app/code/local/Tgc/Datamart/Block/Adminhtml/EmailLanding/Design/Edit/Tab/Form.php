<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Block_Adminhtml_EmailLanding_Design_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'emailLanding_design_form',
            array('legend' => Mage::helper('tgc_datamart')->__('Email Landing Page Design Information'))
        );

        $fieldset->addField('category', 'select', array(
            'label'     => Mage::helper('tgc_datamart')->__('Category'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'category',
            'values'    => $this->_getCategoryOptionArray(),
        ));

        $fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('tgc_datamart')->__('Page Title'),
            'class'     => '',
            'required'  => false,
            'name'      => 'title',
            'after_element_html' => '<br /><small>If left empty, a configurable default value will be used</small>',
        ));

        $fieldset->addField('description', 'textarea', array(
            'label'     => Mage::helper('tgc_datamart')->__('Meta Description'),
            'class'     => '',
            'required'  => false,
            'name'      => 'description',
            'after_element_html' => '<br /><small>If left empty, a configurable default value will be used</small>',
        ));

        $fieldset->addField('keywords', 'textarea', array(
            'label'     => Mage::helper('tgc_datamart')->__('Meta Keywords'),
            'class'     => '',
            'required'  => false,
            'name'      => 'keywords',
            'after_element_html' => '<br /><small>If left empty, a configurable default value will be used</small>',
        ));

        $fieldset->addField('header_id', 'select', array(
            'label'     => Mage::helper('tgc_datamart')->__('Header Block'),
            'class'     => '',
            'required'  => false,
            'name'      => 'header_id',
            'values'    => Mage::getModel('tgc_datamart/adminhtml_system_config_source_cms_block')->toOptionArray(),
            'after_element_html' => '<br /><small>This block will appear above the product listing</small>',
        ));

        $fieldset->addField('footer_id', 'select', array(
            'label'     => Mage::helper('tgc_datamart')->__('Footer Block'),
            'class'     => '',
            'required'  => false,
            'name'      => 'footer_id',
            'values'    => Mage::getModel('tgc_datamart/adminhtml_system_config_source_cms_block')->toOptionArray(),
            'after_element_html' => '<br /><small>This block will appear below the product listing</small>',
        ));

        $fieldset->addField('landing_page_type', 'select', array(
            'label'     => Mage::helper('tgc_datamart')->__('Landing Page Type'),
            'name'      => 'landing_page_type',
            'values'    => Mage::getModel('tgc_datamart/source_landingPage_type')->toOptionArray(),
        ));

        if (Mage::getSingleton('adminhtml/session')->getEmailLandingDesignData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getEmailLandingDesignData());
            Mage::getSingleton('adminhtml/session')->setEmailLandingDesignData(null);
        } elseif (Mage::registry('emailLanding_design_data')) {
            $form->setValues(Mage::registry('emailLanding_design_data')->getData());
        }

        return parent::_prepareForm();
    }

    private function _getCategoryOptionArray()
    {
        $options = Mage::getModel('tgc_datamart/adminhtml_system_config_source_category')->toOptionArray();
        //add currently selected category back into array
        $model = Mage::getModel('tgc_datamart/emailLanding_design')->load($this->getRequest()->getParam('id'));
        if ($model->getId()) {
            $data = array('value' => $model->getCategory(), 'label' => $model->getCategory());
            $options[] = $data;
            asort($options);
        }

        return $options;
    }
}
