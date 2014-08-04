<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */

class RocketWeb_Podcast_Block_Adminhtml_Podcast extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_podcast';
        $this->_blockGroup = 'podcast';
        $this->_headerText = Mage::helper('podcast')->__('Podcast Manager');
        $this->_addButtonLabel = Mage::helper('podcast')->__('Add Podcast');
        parent::__construct();
        $this->setTemplate('rocketweb_podcast/podcasts.phtml');
    }
    
    protected function _prepareLayout()
    {
        $this->setChild('add_new_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('podcast')->__('Add Podcast'),
                    'onclick'   => "setLocation('".$this->getUrl('*/*/new')."')",
                    'class'   => 'add'
                    ))
                );
        /**
         * Display store switcher if system has more one store
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $this->setChild('store_switcher',
                $this->getLayout()->createBlock('adminhtml/store_switcher')
                    ->setUseConfirm(false)
                    ->setSwitchUrl($this->getUrl('*/*/*', array('store'=>null)))
            );
        }
        $this->setChild('grid', $this->getLayout()->createBlock('podcast/adminhtml_podcast_grid', 'podcast.grid'));
        return parent::_prepareLayout();
    }
    
    public function getAddNewButtonHtml()
    {
        return $this->getChildHtml('add_new_button');
    }
    
    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

    public function getStoreSwitcherHtml()
    {
        return $this->getChildHtml('store_switcher');
    }
}