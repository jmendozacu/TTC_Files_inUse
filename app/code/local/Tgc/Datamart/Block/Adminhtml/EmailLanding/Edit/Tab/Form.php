<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Block_Adminhtml_EmailLanding_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'emailLanding_form',
            array('legend' => Mage::helper('tgc_datamart')->__('Email Landing Item Information'))
        );

        $fieldset->addField('category', 'text', array(
            'label'     => Mage::helper('tgc_datamart')->__('Category'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'category',
        ));

        $fieldset->addField('course_id', 'text', array(
            'label'     => Mage::helper('tgc_datamart')->__('Course ID'),
            'class'     => 'required-entry validate-int',
            'required'  => true,
            'name'      => 'course_id',
        ));

        $fieldset->addField('sort_order', 'text', array(
            'label'     => Mage::helper('tgc_datamart')->__('Sort Order'),
            'class'     => 'validate-decimal',
            'required'  => false,
            'name'      => 'sort_order',
        ));

        $fieldset->addField('markdown_flag', 'select', array(
            'label'     => Mage::helper('tgc_datamart')->__('Markdown Flag'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'markdown_flag',
            'values'    => array('0' => 'No', '1' => 'Yes'),
        ));

        $fieldset->addField('special_message', 'text', array(
            'label'     => Mage::helper('tgc_datamart')->__('Special Message'),
            'required'  => false,
            'name'      => 'special_message',
        ));

        $fieldset->addField('date_expires', 'date', array(
            'label'     => Mage::helper('tgc_datamart')->__('Date Expires'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'date_expires',
            'format'    => Varien_Date::DATE_INTERNAL_FORMAT,
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
        ));

        $fieldset->addField('landing_page_type', 'select', array(
            'label'     => Mage::helper('tgc_datamart')->__('Landing Page Type'),
            'name'      => 'landing_page_type',
            'values'    => Mage::getModel('tgc_datamart/source_landingPage_type')->toOptionArray(),
        ));

        if (Mage::getSingleton('adminhtml/session')->getEmailLandingData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getEmailLandingData());
            Mage::getSingleton('adminhtml/session')->setEmailLandingData(null);
        } elseif (Mage::registry('emailLanding_data')) {
            $form->setValues(Mage::registry('emailLanding_data')->getData());
        }

        return parent::_prepareForm();
    }
}
