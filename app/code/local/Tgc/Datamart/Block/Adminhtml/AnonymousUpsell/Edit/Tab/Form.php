<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Block_Adminhtml_AnonymousUpsell_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'anonymousUpsell_form',
            array('legend' => Mage::helper('tgc_datamart')->__('Anonymous Upsell Information'))
        );

        $fieldset->addField('subject_id', 'text', array(
            'label'     => Mage::helper('tgc_datamart')->__('Subject ID'),
            'class'     => 'required-entry validate-int',
            'required'  => true,
            'name'      => 'subject_id',
        ));

        $fieldset->addField('course_id', 'text', array(
            'label'     => Mage::helper('tgc_datamart')->__('Course ID'),
            'class'     => 'required-entry validate-int',
            'required'  => true,
            'name'      => 'course_id',
        ));

        $fieldset->addField('sort_order', 'text', array(
            'label'     => Mage::helper('tgc_datamart')->__('Sort Order'),
            'class'     => 'validate-int',
            'required'  => false,
            'name'      => 'sort_order',
        ));

        if (Mage::getSingleton('adminhtml/session')->getAnonymousUpsellFormData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getAnonymousUpsellFormData());
            Mage::getSingleton('adminhtml/session')->setAnonymousUpsellFormData(null);
        } elseif (Mage::registry('anonymousUpsell_data')) {
            $form->setValues(Mage::registry('anonymousUpsell_data')->getData());
        }

        return parent::_prepareForm();
    }
}
