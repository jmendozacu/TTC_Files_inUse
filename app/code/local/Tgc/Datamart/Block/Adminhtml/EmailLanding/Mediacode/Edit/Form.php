<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Block_Adminhtml_EmailLanding_Mediacode_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
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
            'use_container' => true
        ));
        $mediaCode = Mage::registry('tgc_datamart_landing_media_code');

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('customer')->__('Media Code Information')
        ));

        if (!is_null($mediaCode->getId())) {
            $fieldset->addField('entity_id', 'hidden', array(
                'name'  => 'entity_id'
            ));
        }

        $fieldset->addField('media_code', 'text', array(
            'name'     => 'media_code',
            'label'    => $this->__('Media Code'),
            'required' => true
        ));

        $fieldset->addField('media_code_aliases', 'text', array(
            'name'     => 'media_code_aliases',
            'label'    => $this->__('Aliases'),
            'note'     => $this->__('Comma-separated list of media code aliases/misspellings')
        ));

        $adCodesOptions = array();
        $adCodesCollection = Mage::getModel('tgc_price/adCode')->getCollection()
            ->addFieldToSelect('code')
            ->setOrder('code', Varien_Data_Collection::SORT_ORDER_ASC);
        foreach ($adCodesCollection->getColumnValues('code') as $code) {
            $adCodesOptions[] = array('value' => $code, 'label' => $code);
        }
        $fieldset->addField('ad_code', 'select', array(
            'name'     => 'ad_code',
            'label'    => $this->__('Ad Code'),
            'values'   => $adCodesOptions,
            'required' => true
        ));

        $values = $mediaCode->getData();
        $values['media_code_aliases'] = implode(',', $mediaCode->getMediaCodeAliases());
        $form->setValues($values);
        $this->setForm($form);
    }
}
