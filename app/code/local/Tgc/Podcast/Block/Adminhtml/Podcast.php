<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Podcast_Block_Adminhtml_Podcast extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_podcast';
        $this->_blockGroup = 'tgc_podcast';
        $this->_headerText = Mage::helper('podcast')->__('Podcast Manager');
        $this->_addButtonLabel = Mage::helper('podcast')->__('Add Podcast');

        parent::__construct();
    }
}