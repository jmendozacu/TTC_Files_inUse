<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */


class RocketWeb_Podcast_Block_Adminhtml_Podcast_Edit_Tab_Author extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('podcast_form', array('legend' => Mage::helper('podcast')->__('Author')));
        
        $fieldset->addField('author_name', 'text', array(
            'name'      => 'author_name',
            'label'     => Mage::helper('podcast')->__('Author\'s name'),
            'title'     => Mage::helper('podcast')->__('Author\'s name'),
            'required'  => true,
        ));
        
        $fieldset->addField('author_email', 'text', array(
            'name'      => 'author_email',
            'label'     => Mage::helper('podcast')->__('Author\'s e-mail address'),
            'title'     => Mage::helper('podcast')->__('Author\'s e-mail address'),
            'required'  => true,
        ));

        if(Mage::registry('podcast_data')){
            $form->setValues(Mage::registry('podcast_data')->getData());
        }
        if(!$form->getElement('author_name')->getValue()){
            $form->getElement('author_name')->setValue(Mage::getStoreConfig('rocketweb_podcast/settings/author_name'));
        }
        if(!$form->getElement('author_email')->getValue()){
            $form->getElement('author_email')->setValue(Mage::getStoreConfig('rocketweb_podcast/settings/author_email'));
        }
        
        
        return parent::_prepareForm();
    }

}
