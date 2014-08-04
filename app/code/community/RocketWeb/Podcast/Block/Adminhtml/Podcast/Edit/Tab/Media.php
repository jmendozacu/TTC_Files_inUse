<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */

class RocketWeb_Podcast_Block_Adminhtml_Podcast_Edit_Tab_Media extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('podcast_form', array('legend' => Mage::helper('podcast')->__('Media')));

        $podcast = Mage::getModel('podcast/podcast')->load( $this->getRequest()->getParam('id') );
        
        /* Check max upload size limit (MB) */
        $max_upload = (int)(ini_get('upload_max_filesize'));
        $max_post = (int)(ini_get('post_max_size'));
        $memory_limit = (int)(ini_get('memory_limit'));
        $upload_mb = min($max_upload, $max_post, $memory_limit);
        
        /* Parameters */
        $label = 'Podcast file';
        $label .= ' (max '.$upload_mb.'MB)';
        $note = false;
        $required = true;
        $after_element_html = '<span style="display:block;font-size:11px;">(Maximum '.$upload_mb.'MB)</span>';
        
        if( $podcast->getFileName() ){
            $label = Mage::helper('podcast')->__('New podcast file');
            $note = '<a href="'.Mage::helper('podcast')->getPodcastDirectoryUrl().$podcast->getFileName().'" target="_blank">' . Mage::helper('podcast')->__('View current file') . '</a>';
            $required = false;
            $after_element_html .= '<span class="hint" style="display:block;font-size:11px;">(' . Mage::helper('podcast')->__('Previous podcast file will be removed') . ')</span>';
        }
        
        $fieldset->addField('podcast_file', 'file', array(
            'label'                 => $label,
            'note'                  => $note,
            'name'                  => 'podcast_file',
            'required'              => $required,
            'allow_empty'           => true, 
            'after_element_html'    => $after_element_html,
        )); 
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
//        $fieldset->addField('podcast_file', 'file', array(
//            'name'      => 'podcast_file',
//            'label'     => Mage::helper('podcast')->__('Podcast file'),
//            'title'     => Mage::helper('podcast')->__('Podcast file'),
//            'required'  => true,
//        ));
//
//        if(Mage::registry('podcast_data'))
//        {
//            $form->setValues(Mage::registry('podcast_data')->getData());
//        }
        
        return parent::_prepareForm();
    }

}
