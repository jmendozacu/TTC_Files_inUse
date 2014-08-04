<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @boutique    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Block_Adminhtml_BoutiqueHeroCarousel_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
            array(
                'add_images'    => true,
                'add_widgets'   => true,
                'add_variables' => true,
            )
        );

        $fieldset = $form->addFieldset(
            'boutiqueHeroCarousel_form',
            array('legend' => Mage::helper('tgc_boutique')->__('Carousel Item Information'))
        );

        $fieldset->addField('boutique_id', 'select', array(
            'label'     => Mage::helper('tgc_boutique')->__('Boutique'),
            'name'      => 'boutique_id',
            'values'    => Mage::getModel('tgc_boutique/source_boutiques')->toItemOptionArray(),
        ));

        $fieldset->addField('boutique_page_id', 'select', array(
            'label'     => Mage::helper('tgc_boutique')->__('Boutique Page'),
            'name'      => 'boutique_page_id',
            'values'    => Mage::getModel('tgc_boutique/source_pages')->toItemOptionArray(),
        ));

        $fieldset->addField('is_active', 'select', array(
            'label'     => Mage::helper('tgc_boutique')->__('Is Active'),
            'name'      => 'is_active',
            'values'    => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value'     => '1',
        ));

        $fieldset->addField('description', 'editor', array(
            'label'     => Mage::helper('tgc_boutique')->__('Billboard'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'description',
            'config'    => $wysiwygConfig,
            'wysiwyg'   => true,
            'style'     => 'width:300px; height:100px;',
        ));

        $fieldset->addField('sort_order', 'text', array(
            'label'     => Mage::helper('tgc_boutique')->__('Sort Order'),
            'class'     => 'validate-int',
            'required'  => false,
            'name'      => 'sort_order',
        ));

        $fieldset->addField('tab_title', 'text', array(
            'label'     => Mage::helper('tgc_boutique')->__('Tab Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'tab_title',
        ));

        $fieldset->addField('tab_description', 'text', array(
            'label'     => Mage::helper('tgc_boutique')->__('Tab Description'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'tab_description',
        ));

        $fieldset->addField('user_type', 'select', array(
            'label'     => Mage::helper('tgc_boutique')->__('User Type'),
            'required'  => false,
            'name'      => 'user_type',
            'values'    => Mage::getModel('tgc_boutique/source_userType')->toOptionArray(),
            'value'     => Tgc_Boutique_Model_Source_UserType::ALL_USERS,
        ));

        $fieldset->addField('store', 'select', array(
            'label'     => Mage::helper('tgc_boutique')->__('Store'),
            'required'  => false,
            'name'      => 'store',
            'values'    => Mage::getModel('tgc_boutique/source_store')->toOptionArray(),
            'value'     => '0',
        ));

        $data = array();
        if (Mage::getSingleton('adminhtml/session')->getBoutiqueHeroCarouselFormData()) {
            $data = Mage::getSingleton('adminhtml/session')->getBoutiqueHeroCarouselFormData();
            Mage::getSingleton('adminhtml/session')->setBoutiqueHeroCarouselFormData(null);
        } elseif (Mage::registry('boutiqueHeroCarousel_data')) {
            $data = Mage::registry('boutiqueHeroCarousel_data')->getData();
        }

        if (isset($data['active_from']) && !empty($data['active_from'])) {
            $data['active_from'] = Mage::app()->getLocale()->date($data['active_from'], null);
        }
        if (isset($data['active_to']) && !empty($data['active_to'])) {
            $data['active_to'] = Mage::app()->getLocale()->date($data['active_to'], null);
        }

        $fieldset->addField('active_from', 'date', array(
            'label'     => Mage::helper('tgc_dax')->__('Active From'),
            'required'  => false,
            'name'      => 'active_from',
            'format'    => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'time'      => true,
            'timezone'  => Mage::app()->getLocale()->getTimezone(),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'value'     => isset($data['active_from']) ? $data['active_from'] : null,
        ));

        $fieldset->addField('active_to', 'date', array(
            'label'     => Mage::helper('tgc_dax')->__('Active To'),
            'required'  => false,
            'name'      => 'active_to',
            'format'    => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'time'      => true,
            'timezone'  => Mage::app()->getLocale()->getTimezone(),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'value'     => isset($data['active_to']) ? $data['active_to'] : null,
        ));

        $form->setValues($data);

        return parent::_prepareForm();
    }
}
