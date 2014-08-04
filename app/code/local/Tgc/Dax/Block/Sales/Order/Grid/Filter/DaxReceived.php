<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Block_Sales_Order_Grid_Filter_DaxReceived  extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Select
{
    protected function _getOptions()
    {
        $emptyOption = array(array('value' => null, 'label' => ''));
        $options = Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray();

        return array_merge($emptyOption, $options);
    }
}
