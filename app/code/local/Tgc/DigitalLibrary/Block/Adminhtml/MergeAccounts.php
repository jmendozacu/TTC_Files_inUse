<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_Adminhtml_MergeAccounts extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_mergeAccounts';
        $this->_blockGroup = 'tgc_dl';
        $this->_headerText = Mage::helper('tgc_dl')->__('Merged Customer Accounts Manager');
        $this->_addButtonLabel = Mage::helper('tgc_dax')->__('Add Merged Account');

        parent::__construct();
    }
}
