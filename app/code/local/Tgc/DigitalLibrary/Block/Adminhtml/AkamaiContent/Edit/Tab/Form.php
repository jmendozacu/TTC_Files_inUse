<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_Adminhtml_AkamaiContent_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'akamaiContent_form',
            array('legend' => Mage::helper('tgc_dl')->__('Akamai Content Information'))
        );

        $fieldset->addField('course_id', 'text', array(
            'label'     => Mage::helper('tgc_dl')->__('Course ID'),
            'required'  => true,
            'name'      => 'course_id',
        ));

        $fieldset->addField('guidebook_file_name', 'text', array(
            'label'     => Mage::helper('tgc_dl')->__('Guidebook File Name'),
            'required'  => false,
            'name'      => 'guidebook_file_name',
        ));

        $fieldset->addField('guidebook_url_prefix', 'text', array(
            'label'     => Mage::helper('tgc_dl')->__('Guidebook URL Prefix'),
            'required'  => true,
            'class'     => 'validate-url',
            'name'      => 'guidebook_url_prefix',
        ));

        $fieldset->addField('transcript_file_name', 'text', array(
            'label'     => Mage::helper('tgc_dl')->__('Transcript File Name'),
            'required'  => false,
            'name'      => 'transcript_file_name',
        ));

        $fieldset->addField('transcript_url_prefix', 'text', array(
            'label'     => Mage::helper('tgc_dl')->__('Transcript URL Prefix'),
            'required'  => true,
            'class'     => 'validate-url',
            'name'      => 'transcript_url_prefix',
        ));

        if (Mage::getSingleton('adminhtml/session')->getAkamaiContentData())
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getAkamaiContentData());
            Mage::getSingleton('adminhtml/session')->setAkamaiContentData(null);
        } elseif (Mage::registry('akamaiContent_data')) {
            $form->setValues(Mage::registry('akamaiContent_data')->getData());
        }

        return parent::_prepareForm();
    }
}
