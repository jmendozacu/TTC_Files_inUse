<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Tgc_Sales
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Sales_Block_Adminhtml_Order extends Mage_Adminhtml_Block_Sales_Order
{
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'tgc_sales';
        $this->_controller = 'adminhtml_order';
    }
}
