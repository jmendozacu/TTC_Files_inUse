<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Block_Adminhtml_EmailLanding_Design extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_emailLanding_design';
        $this->_blockGroup = 'tgc_datamart';
        $this->_headerText = Mage::helper('tgc_datamart')->__('Landing Page Designs');
        $this->_addButtonLabel = Mage::helper('tgc_datamart')->__('New Landing Page Design');

        parent::__construct();
    }
}
