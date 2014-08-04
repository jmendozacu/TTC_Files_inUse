<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Block_Adminhtml_EmailLanding_Banner_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare form for render
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id'            => 'edit_form',
            'action'        => $this->getUrl('*/*/save'),
            'method'        => 'post',
            'use_container' => true,
            'enctype'      => 'multipart/form-data'
        ));
        $banner = Mage::registry('tgc_datamart_landing_banner');

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('customer')->__('Banner Information')
        ));

        $fieldset->addType(
            'image',
            Mage::getConfig()->getBlockClassName('tgc_datamart/adminhtml_emailLanding_banner_edit_form_element_image')
        );

        if (!is_null($banner->getId())) {
            $fieldset->addField('banner_id', 'hidden', array(
                'name'  => 'banner_id'
            ));
        }

        $fieldset->addField('title', 'text', array(
            'name'     => 'title',
            'label'    => $this->__('Title'),
            'required' => true
        ));

        $adCodesOptions = array();
        $adCodesCollection = Mage::getModel('tgc_price/adCode')->getCollection()
            ->addFieldToSelect('code')
            ->setOrder('code', Varien_Data_Collection::SORT_ORDER_ASC);
        foreach ($adCodesCollection->getColumnValues('code') as $code) {
            $adCodesOptions[] = array('value' => $code, 'label' => $code);
        }
        $fieldset->addField('ad_codes', 'multiselect', array(
            'name'     => 'ad_codes[]',
            'label'    => $this->__('Ad Codes'),
            'values'   => $adCodesOptions,
            'required' => true
        ));

        $fieldset->addField('mobile_image', 'image', array(
            'name'     => 'mobile_image',
            'label'    => $this->__('Mobile Image'),
            'required' => true
        ));

        $fieldset->addField('desktop_image', 'image', array(
            'name'     => 'desktop_image',
            'label'    => $this->__('Desktop Image'),
            'required' => true
        ));

        $fieldset->addField('set_sku', 'text', array(
            'label'     => $this->__('Set SKU'),
            'name'      => 'set_sku'
        ));

        $fieldset->addField('set_text', 'editor', array(
            'label'     => $this->__('Set Text'),
            'name'      => 'set_text',
            'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(array(
                'add_variables' => false,
                'add_widgets'   => false,
                'add_images'    => false
            ))
        ));

        $form->setValues($banner->getData());
        $this->setForm($form);
    }
}
