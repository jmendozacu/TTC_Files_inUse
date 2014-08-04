<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Block_Adminhtml_EmailLanding_Mediacode extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'tgc_datamart';
        $this->_controller = 'adminhtml_emailLanding_mediacode';
        $this->_headerText = $this->__('Landing Page Media Codes');
        $this->_addButtonLabel = $this->__('Add New Media Code');
        parent::__construct();
    }
}
