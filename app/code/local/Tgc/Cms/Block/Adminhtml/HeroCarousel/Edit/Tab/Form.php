<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Block_Adminhtml_HeroCarousel_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
            array(
                'add_images' => true,
                'add_widgets' => true,
                'add_variables' => true,
            )
        );

        $fieldset = $form->addFieldset(
            'heroCarousel_form',
            array('legend' => Mage::helper('tgc_cms')->__('Carousel Item Information'))
        );

        $fieldset->addField('is_active', 'select', array(
            'label'     => Mage::helper('tgc_cms')->__('Is Active'),
            'name'      => 'is_active',
            'values'    => array('0' => 'No', '1' => 'Yes'),
            'value'     => 'Yes',
        ));

        $defDescription = <<<DEF_DESCRIPTION
<div class="hero-img">
    <img src="{{skin url='images/tgc/home-hero-img.png'}}" alt="Image hero"/>
</div>
<div class="hero-desc">
    <h2>You Might Like This Course Based on This Very Compelling Headline.</h2>
    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit nesciunt.</p>
    <a class="button" href="#">Learn More</a>
</div>
DEF_DESCRIPTION;

        $fieldset->addField('description', 'editor', array(
            'label'     => Mage::helper('tgc_cms')->__('Billboard'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'description',
            'config'    => $wysiwygConfig,
            'wysiwyg'   => true,
            'style'     => 'width:500px; height:300px;',
        ));

        $defMobileDescription = <<<DEF_MOBILE_DESCRIPTION
<div class="hero-img">
    <img src="{{skin url='images/tgc/home-hero-mob-img.png'}}" alt="Image hero"/>
</div>
<div class="hero-desc">
    <h2>Chat with Professor</h2>
    <p>Sed ut perspiciatis unde omnis iste natus error sit</p>
    <a class="button" href="#">Learn More</a>
</div>
DEF_MOBILE_DESCRIPTION;

        $fieldset->addField('mobile_description', 'editor', array(
            'label'     => Mage::helper('tgc_cms')->__('Mobile Billboard'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'mobile_description',
            'config'    => $wysiwygConfig,
            'wysiwyg'   => true,
            'style'     => 'width:500px; height:300px;',
        ));

        $baseUrl = Mage::getBaseUrl();
        $ukDetails = <<<UK_DETAILS
<a href="#" title="More Details">More Details</a>
UK_DETAILS;

        $fieldset->addField('uk_details', 'editor', array(
            'label'     => Mage::helper('tgc_cms')->__('UK Popover Content'),
            'required'  => false,
            'name'      => 'uk_details',
            'config'    => $wysiwygConfig,
            'wysiwyg'   => true,
            'style'     => 'width:500px; height:300px;',
            'after_element_html' => '<p><small>This field is only shown on the UK site</small></p>',
        ));

        $fieldset->addField('sort_order', 'text', array(
            'label'     => Mage::helper('tgc_cms')->__('Sort Order'),
            'class'     => 'validate-int',
            'required'  => false,
            'name'      => 'sort_order',
        ));

        $fieldset->addField('tab_title', 'text', array(
            'label'     => Mage::helper('tgc_cms')->__('Tab Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'tab_title',
        ));

        $fieldset->addField('tab_description', 'text', array(
            'label'     => Mage::helper('tgc_cms')->__('Tab Description'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'tab_description',
        ));

        $fieldset->addField('user_type', 'select', array(
            'label'     => Mage::helper('tgc_cms')->__('User Type'),
            'required'  => false,
            'name'      => 'user_type',
            'values'    => Mage::getModel('tgc_cms/source_userType')->toOptionArray(),
            'value'     => '2',
        ));

        $fieldset->addField('store', 'select', array(
            'label'     => Mage::helper('tgc_cms')->__('Store'),
            'required'  => false,
            'name'      => 'store',
            'values'    => Mage::getModel('tgc_cms/source_store')->toOptionArray(),
            'value'     => '0',
        ));

        $data = array();
        if (Mage::getSingleton('adminhtml/session')->getHeroCarouselFormData()) {
            $data = Mage::getSingleton('adminhtml/session')->getHeroCarouselFormData();
            Mage::getSingleton('adminhtml/session')->setHeroCarouselFormData(null);
        } elseif (Mage::registry('heroCarousel_data')) {
            $data = Mage::registry('heroCarousel_data')->getData();
        }

        // If This Form is Adding a new Item, else the contents are not loaded.
        if (!(Mage::registry('heroCarousel_data') && Mage::registry('heroCarousel_data')->getId())) {
            if (!isset($data['description']) || empty($data['description'])) {
                $data['description'] = $defDescription;
            }
            if (!isset($data['mobile_description']) || empty($data['mobile_description'])) {
                $data['mobile_description'] = $defMobileDescription;
            }
            if (!isset($data['uk_details']) || empty($data['uk_details'])) {
                $data['uk_details'] = $ukDetails;
            }
            if (isset($data['active_from']) && !empty($data['active_from'])) {
                $data['active_from'] = Mage::app()->getLocale()->date($data['active_from'], null);
            }
            if (isset($data['active_to']) && !empty($data['active_to'])) {
                $data['active_to'] = Mage::app()->getLocale()->date($data['active_to'], null);
            }
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
