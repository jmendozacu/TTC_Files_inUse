<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */

class RocketWeb_Podcast_Block_Adminhtml_Podcast_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'podcast';
        $this->_controller = 'adminhtml_podcast';
        
        $this->_updateButton('save', 'label', Mage::helper('podcast')->__('Save Podcast'));
        $this->_updateButton('delete', 'label', Mage::helper('podcast')->__('Delete Podcast'));
  
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }
    
    public function getHeaderText()
    {
        if( Mage::registry('podcast_data') && Mage::registry('podcast_data')->getId() ) {
            return Mage::helper('podcast')->__("Edit Podcast '%s'", $this->htmlEscape(Mage::registry('podcast_data')->getTitle()));
        } else {
            return Mage::helper('podcast')->__('Add Podcast');
        }
    }
    
}