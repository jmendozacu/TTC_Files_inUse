<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_Adminhtml_CrossPlatformResume_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'crossPlatformResume_form',
            array('legend' => Mage::helper('tgc_dl')->__('Resume Data Information'))
        );

        $fieldset->addField('lecture_id', 'text', array(
            'label'     => Mage::helper('tgc_dl')->__('Lecture ID'),
            'required'  => true,
            'name'      => 'lecture_id',
        ));

        $fieldset->addField('web_user_id', 'text', array(
            'label'     => Mage::helper('tgc_dl')->__('Web User ID'),
            'required'  => true,
            'name'      => 'web_user_id',
        ));

        $fieldset->addField('progress', 'text', array(
            'label'     => Mage::helper('tgc_dl')->__('Progress'),
            'required'  => true,
            'class'     => 'required-entry validate-int',
            'name'      => 'progress',
            'after_element_html' => '<p><small>This value is in seconds</small></p>',
        ));

        $data = array();
        if (Mage::getSingleton('adminhtml/session')->getCrossPlatformResumeData()) {
            $data = Mage::getSingleton('adminhtml/session')->getCrossPlatformResumeData();
            Mage::getSingleton('adminhtml/session')->getCrossPlatformResumeData(null);
        } elseif (Mage::registry('crossPlatformResume_data')) {
            $data = Mage::registry('crossPlatformResume_data')->getData();
        }

        $fieldset->addField('download_date', 'date', array(
            'label'     => Mage::helper('tgc_dl')->__('Download Date'),
            'required'  => false,
            'name'      => 'download_date',
            'format'    => Varien_Date::DATE_INTERNAL_FORMAT,
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'value'     => isset($data['download_date']) ? $data['download_date'] : null,
        ));

        $fieldset->addField('stream_date', 'date', array(
            'label'     => Mage::helper('tgc_dl')->__('Stream Date'),
            'required'  => false,
            'name'      => 'stream_date',
            'format'    => Varien_Date::DATE_INTERNAL_FORMAT,
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'value'     => isset($data['stream_date']) ? $data['stream_date'] : null,
        ));

        $fieldset->addField('format', 'select', array(
            'label'     => Mage::helper('tgc_dl')->__('Format'),
            'required'  => true,
            'name'      => 'format',
            'values'    => Mage::getModel('tgc_dl/source_format')->toOptionArray(),
        ));

        $form->setValues($data);

        return parent::_prepareForm();
    }
}
