<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */


class RocketWeb_Podcast_Block_Adminhtml_Podcast_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('podcast_form', array('legend' => Mage::helper('podcast')->__('Podcast Information')));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('podcast')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name' => 'stores[]',
                'label' => Mage::helper('cms')->__('Store View'),
                'title' => Mage::helper('cms')->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        }
        
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('podcast')->__('Status'),
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('podcast')->__('Enabled'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('podcast')->__('Disabled'),
                ),
                array(
                    'value' => 3,
                    'label' => Mage::helper('podcast')->__('Hidden'),
                ),
            ),
            'after_element_html' => '<span class="hint" style="display:block;font-size:11px;">(' . Mage::helper('podcast')->__('Hidden Pages will not show in the podcast but can still be accessed directly') . ')</span>',
        ));
        
        try{
            $config = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
            $config->setData(
                    Mage::helper('podcast')->
                        recursiveReplace('/podcasts_admin/', 
                                          '/'.(string)Mage::app()->getConfig()->getNode('admin/routers/adminhtml/args/frontName').'/', 
                                          $config->getData()));
        }
        catch (Exception $ex) {
            $config = null;
        }
        
        $fieldset->addField('short_content', 'editor', array(
            'label'     => Mage::helper('podcast')->__('Short Description'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'short_content',
            'style'     => 'width: 520px;',
            'config'    => $config,
            'wysiwyg'   => true,
 
        ));
        
        $fieldset->addField('long_content', 'editor', array(
            'label'     => Mage::helper('podcast')->__('Long Description'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'long_content',
            'style'     => 'width: 520px;',
            'config'    => $config,
            'wysiwyg'   => true,
 
        ));
        
        $fieldset->addField('meta_keywords', 'editor', array(
            'name' => 'meta_keywords',
            'label' => Mage::helper('podcast')->__('Meta Keywords'),
            'title' => Mage::helper('podcast')->__('Meta Keywords'),
            'style' => 'width: 520px;',
        ));
        
        $fieldset = $form->addFieldset('podcast_options', array('legend' => Mage::helper('podcast')->__('Advanced Podcast Options')));
        
        $outputFormat = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);

        $fieldset->addField('created_time', 'date', array(
            'name' => 'created_time',
            'label' => $this->__('Created on'),
            'title' => $this->__('Created on'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => $outputFormat,
            'time' => true,
        ));



        if (Mage::getSingleton('adminhtml/session')->getPodcastData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getPodcastData());
            Mage::getSingleton('adminhtml/session')->setPodcastData(null);
        } elseif ($data = Mage::registry('podcast_data')) {

            $form->setValues(Mage::registry('podcast_data')->getData());

            if ($data->getData('created_time')) {
                $form->getElement('created_time')->setValue(
                    Mage::app()->getLocale()->date($data->getData('created_time'), Varien_Date::DATETIME_INTERNAL_FORMAT)
                );
            }
        }
        
        return parent::_prepareForm();
    }

}
