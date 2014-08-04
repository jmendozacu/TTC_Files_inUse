<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Block_Adminhtml_EmailUnsubscribe_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'emailUnsubscribe_form',
            array('legend' => Mage::helper('tgc_dax')->__('Email Unsubscribe Information'))
        );

        $fieldset->addField('web_key', 'text', array(
            'label'     => Mage::helper('tgc_dax')->__('Web Key'),
            'class'     => 'validate-digits',
            'required'  => true,
            'name'      => 'web_key',
        ));

        $fieldset->addField('customer_id', 'text', array(
            'label'     => Mage::helper('tgc_dax')->__('Customer ID'),
            'class'     => 'validate-digits',
            'required'  => true,
            'name'      => 'customer_id',
        ));

        $fieldset->addField('email', 'text', array(
            'label'     => Mage::helper('tgc_dax')->__('Email'),
            'class'     => 'validate-email',
            'required'  => true,
            'name'      => 'email',
        ));

        $fieldset->addField('unsubscribe_date', 'date', array(
            'label'     => Mage::helper('tgc_dax')->__('Unsubscribe Date'),
            'required'  => true,
            'name'      => 'unsubscribe_date',
            'format'    => Varien_Date::DATE_INTERNAL_FORMAT,
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
        ));

        $fieldset->addField('email_campaign', 'text', array(
            'label'     => Mage::helper('tgc_dax')->__('Email Campaign'),
            'required'  => true,
            'name'      => 'email_campaign',
        ));

        $fieldset->addField('is_archived', 'select', array(
            'label'     => Mage::helper('tgc_dax')->__('Is Archived'),
            'name'      => 'is_archived',
            'values'    => array('0' => 'No', '1' => 'Yes'),
        ));

        if (Mage::getSingleton('adminhtml/session')->getEmailUnsubscribeData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getEmailUnsubscribeData());
            Mage::getSingleton('adminhtml/session')->setEmailUnsubscribeData(null);
        } elseif (Mage::registry('emailUnsubscribe_data')) {
            $form->setValues(Mage::registry('emailUnsubscribe_data')->getData());
        }

        return parent::_prepareForm();
    }
}
