<?php
/**
 * flat rate shipping model
 *
 * @method int getWebsiteId() getWebsiteId() Returns website id
 * @method int getCustomerGroupId() getCustomerGroupId() Returns customer group ID
 * @method float getShippingPrice() getShippingPrice() Returns shipping price
 *  
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Shipping
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Shipping_Model_FlatRate extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_shipping/flatRate');
    }
}
