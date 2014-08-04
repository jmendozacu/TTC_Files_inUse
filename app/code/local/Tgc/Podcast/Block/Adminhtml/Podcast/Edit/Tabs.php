<?php

/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Podcast_Block_Adminhtml_Podcast_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('podcast_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('podcast')->__('Podcast Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('podcast')->__('Podcast Information'),
          'title'     => Mage::helper('podcast')->__('Podcast Information'),
          'content'   => $this->getLayout()->createBlock('podcast/adminhtml_podcast_edit_tab_form')->toHtml(),
      ));
	  
      $this->addTab('media_section', array(
          'label'     => Mage::helper('podcast')->__('Media'),
          'title'     => Mage::helper('podcast')->__('Media'),
          'content'   => $this->getLayout()->createBlock('podcast/adminhtml_podcast_edit_tab_media')->toHtml(),
      ));
      
      $this->addTab('author_section', array(
          'label'     => Mage::helper('podcast')->__('Author'),
          'title'     => Mage::helper('podcast')->__('Author'),
          'content'   => $this->getLayout()->createBlock('podcast/adminhtml_podcast_edit_tab_author')->toHtml(),
      ));

      $this->addTab('products_section', array(
          'label'     => Mage::helper('podcast')->__('Related Courses'),
          'url'       => $this->getUrl('*/*/products', array('_current' => true)),
          'class'     => 'ajax',
      ));

      return parent::_beforeToHtml();
  }
}
