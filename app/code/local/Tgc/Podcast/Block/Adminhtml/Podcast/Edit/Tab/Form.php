<?php
/**
 * @category   Tgc
 * @package    Tgc_Podcast
 * @copyright  Copyright (c) 2014
 * @author     Guidance
 */

class Tgc_Podcast_Block_Adminhtml_Podcast_Edit_Tab_Form extends RocketWeb_Podcast_Block_Adminhtml_Podcast_Edit_Tab_Form
{

    protected function _prepareForm()
    {

        parent::_prepareForm();

        $form = $this->getForm();

        $fieldset = $form->getElement('podcast_form');
        $fieldset->removeField('short_content');

        try {
            $config = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
            $config->setData(
                Mage::helper('podcast')->
                    recursiveReplace('/podcasts_admin/',
                        '/' . (string)Mage::app()->getConfig()->getNode('admin/routers/adminhtml/args/frontName') . '/',
                        $config->getData()));
        } catch (Exception $ex) {
            $config = null;
        }

        $fieldset->addField('episode_number', 'text', array(
            'label' => Mage::helper('podcast')->__('Episode Number'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'episode_number',
            'after_element_html' => '<p>(' . Mage::helper('podcast')->__('For example: Episode 5') . ')</p>',
        ), 'title');

        $fieldset->addField('episode_duration', 'text', array(
            'label' => Mage::helper('podcast')->__('Episode Duration'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'episode_duration',
            'after_element_html' => '<p>(' . Mage::helper('podcast')->__('For example: 01:25:34 (hour:min:sec)') . ')</p>',
        ), 'episode_number');

        $fieldset->addField('episode_image', 'image', array(
            'label' => Mage::helper('events')->__('Episode Image'),
            'required' => true,
            'name' => 'episode_image',
            'note' => Mage::helper('podcast')->__('<p>(upload image files only, 240px by 152px)</p>')
        ), 'status');

        $fieldset->addField('short_content', 'editor', array(
            'label' => Mage::helper('podcast')->__('In this Podcast Listen To'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'short_content',
            'style' => 'width: 520px;',
            'config' => $config,
            'wysiwyg' => true,
            'after_element_html' => '<p>(' . Mage::helper('podcast')->__('Should be an html unordered list of items') . ')</p>',
        ), 'episode_image');

        $fieldset->addField('url_key', 'text', array(
            'label' => Mage::helper('podcast')->__('Url Key'),
            'name' => 'url_key'
        ), 'url_key');

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
        return $this;
    }

}
