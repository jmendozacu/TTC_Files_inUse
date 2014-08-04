<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Block_Adminhtml_EmailUnsubscribe extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_emailUnsubscribe';
        $this->_blockGroup = 'tgc_dax';
        $this->_headerText = Mage::helper('tgc_dax')->__('Email Unsubscribe Manager');
        $this->_addButtonLabel = Mage::helper('tgc_dax')->__('Add Email Unsubscribe');

        parent::__construct();
    }
}
