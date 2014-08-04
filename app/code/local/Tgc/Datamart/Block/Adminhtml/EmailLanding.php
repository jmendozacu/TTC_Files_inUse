<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Block_Adminhtml_EmailLanding extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_emailLanding';
        $this->_blockGroup = 'tgc_datamart';
        $this->_headerText = Mage::helper('tgc_datamart')->__('Landing Page Categories');
        $this->_addButtonLabel = Mage::helper('tgc_datamart')->__('Add Landing Page Category');

        parent::__construct();
    }
}
