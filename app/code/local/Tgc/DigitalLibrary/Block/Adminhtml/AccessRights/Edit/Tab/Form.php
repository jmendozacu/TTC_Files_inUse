<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_Adminhtml_AccessRights_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'accessRights_form',
            array('legend' => Mage::helper('tgc_dl')->__('Access Rights Information'))
        );

        $fieldset->addField('course_id', 'text', array(
            'label'     => Mage::helper('tgc_dl')->__('Product ID'),
            'required'  => true,
            'name'      => 'course_id',
        ));

        $fieldset->addField('web_user_id', 'text', array(
            'label'     => Mage::helper('tgc_dl')->__('Web User ID'),
            'required'  => true,
            'name'      => 'web_user_id',
        ));

        $fieldset->addField('format', 'select', array(
            'label'     => Mage::helper('tgc_dl')->__('Format'),
            'name'      => 'format',
            'values'    => Mage::getModel('tgc_dl/source_format')->toOptionArray(),
        ));

        $fieldset->addField('date_purchased', 'date', array(
            'label'     => Mage::helper('tgc_dl')->__('Date Purchased'),
            'required'  => true,
            'name'      => 'date_purchased',
            'format'    => Varien_Date::DATE_INTERNAL_FORMAT,
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
        ));

        $fieldset->addField('is_downloadable', 'select', array(
            'label'     => Mage::helper('tgc_dl')->__('Downloadable'),
            'name'      => 'is_downloadable',
            'values'    => array('0' => 'No', '1' => 'Yes'),
        ));

        $fieldset->addField('digital_transcript_purchased', 'select', array(
            'label'     => Mage::helper('tgc_dl')->__('Digital Transcript Purchased'),
            'name'      => 'digital_transcript_purchased',
            'values'    => array('0' => 'No', '1' => 'Yes'),
        ));

        if (Mage::getSingleton('adminhtml/session')->getAccessRightsData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getAccessRightsData());
            Mage::getSingleton('adminhtml/session')->setAccessRightsData(null);
        } elseif (Mage::registry('accessRights_data')) {
            $form->setValues(Mage::registry('accessRights_data')->getData());
        }

        return parent::_prepareForm();
    }
}
