<?php
/**
 * Bazaarvoice
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Bazaarvoice
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Bazaarvoice_Block_Adminhtml_System_Convert_Gui extends Mage_Adminhtml_Block_System_Convert_Gui
{
    protected function _prepareLayout()
    {
        $this->setChild('grid',
            $this->getLayout()->createBlock('tgc_bv/adminhtml_system_convert_gui_grid',
                $this->_controller . '.grid')->setSaveParametersInSession(true) );

        return Mage_Adminhtml_Block_Widget_Container::_prepareLayout();
    }
}
