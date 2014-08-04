<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Block_Adminhtml_BoutiquePages_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset(
            'boutiquePages_form',
            array('legend' => Mage::helper('tgc_boutique')->__('Boutique Page Information'))
        );

        $fieldset->addField('page_title', 'text', array(
            'label'     => Mage::helper('tgc_boutique')->__('Page Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'page_title',
        ));

        $fieldset->addField('url_key', 'text', array(
            'label'     => Mage::helper('tgc_boutique')->__('Page URL key'),
            'class'     => 'required-entry validate-boutique-identifier',
            'required'  => true,
            'name'      => 'url_key',
        ));

        $fieldset->addField('header_block', 'select', array(
            'label'     => Mage::helper('tgc_boutique')->__('Header Block'),
            'name'      => 'header_block',
            'required'  => true,
            'values'    => Mage::getModel('tgc_boutique/source_identifier')->toOptionArray(),
        ));

        $fieldset->addField('content_block', 'select', array(
            'label'     => Mage::helper('tgc_boutique')->__('Content Block'),
            'required'  => true,
            'name'      => 'content_block',
            'values'    => Mage::getModel('tgc_boutique/source_identifier')->toOptionArray(),
        ));

        $fieldset->addField('footer_block', 'select', array(
            'label'     => Mage::helper('tgc_boutique')->__('Footer Block'),
            'name'      => 'footer_block',
            'required'  => false,
            'values'    => Mage::getModel('tgc_boutique/source_identifier')->toOptionArray(),
        ));

        $fieldset->addField('store_id', 'select', array(
            'label'     => Mage::helper('tgc_boutique')->__('Store'),
            'required'  => false,
            'name'      => 'store_id',
            'values'    => Mage::getModel('tgc_boutique/source_store')->toOptionArray(),
            'value'     => '0',
        ));

        $fieldset->addField('sort_order', 'text', array(
            'label'     => Mage::helper('tgc_boutique')->__('Sort Order'),
            'class'     => 'validate-int',
            'name'      => 'sort_order',
        ));

        $fieldset->addField('meta_description', 'textarea', array(
            'label'     => Mage::helper('tgc_boutique')->__('Meta Description'),
            'required'  => false,
            'name'      => 'meta_description',
        ));

        $fieldset->addField('meta_keywords', 'textarea', array(
            'label'     => Mage::helper('tgc_boutique')->__('Meta Keywords'),
            'required'  => false,
            'name'      => 'meta_keywords',
        ));

        $fieldset->addField('disable_carousel', 'select', array(
            'label'     => Mage::helper('tgc_boutique')->__('Disable Hero Carousel for this Page?'),
            'name'      => 'disable_carousel',
            'values'    => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));

        $data = array();
        if (Mage::getSingleton('adminhtml/session')->getBoutiquePagesFormData()) {
            $data = Mage::getSingleton('adminhtml/session')->getBoutiquePagesFormData();
            Mage::getSingleton('adminhtml/session')->setBoutiquePagesFormData(null);
        } elseif (Mage::registry('boutiquePages_data')) {
            $data = Mage::registry('boutiquePages_data')->getData();
        }

        $form->setValues($data);

        return parent::_prepareForm();
    }
}
