<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Events_Block_Adminhtml_Locations extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_locations';
        $this->_blockGroup = 'tgc_events';
        $this->_headerText = Mage::helper('events')->__('Location Manager');
        $this->_addButtonLabel = Mage::helper('events')->__('Add Location');

        parent::__construct();
    }
}