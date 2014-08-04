<?php

class Tgc_Events_Block_Adminhtml_Events_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('events_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('events')->__('Event Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
          'label'     => Mage::helper('events')->__('Event Information'),
          'title'     => Mage::helper('events')->__('Event Information'),
          'content'   => $this->getLayout()->createBlock('events/adminhtml_events_edit_tab_form')->toHtml(),
        ));

        $this->addTab('meta_form_section', array(
          'label'     => Mage::helper('events')->__('Meta Information'),
          'title'     => Mage::helper('events')->__('Meta Information'),
          'content'   => $this->getLayout()->createBlock('events/adminhtml_events_edit_tab_meta')->toHtml(),
        ));

        $this->addTab('products_section', array(
            'label'     => Mage::helper('events')->__('Related Courses'),
            'url'       => $this->getUrl('*/*/products', array('_current' => true)),
            'class'     => 'ajax',
        ));

        $this->addTab('professors_section', array(
          'label'     => Mage::helper('events')->__('Related Professors'),
          'url'       => $this->getUrl('*/*/professors', array('_current' => true)),
          'class'     => 'ajax',
        ));

        return parent::_beforeToHtml();
    }
}