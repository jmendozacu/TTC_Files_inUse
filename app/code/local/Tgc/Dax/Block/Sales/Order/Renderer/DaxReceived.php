<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Block_Sales_Order_Renderer_DaxReceived  extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $value   = $row->getData($this->getColumn()->getIndex());
        $options = Mage::getSingleton('adminhtml/system_config_source_yesno')->toArray();

        return $options[$value];
    }
}
