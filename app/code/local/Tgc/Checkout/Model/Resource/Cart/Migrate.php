<?php

/**
* @author      Guidance Magento Team <magento@guidance.com>
* @category    Tgc
* @package     Cart
* @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
*/

class Tgc_Checkout_Model_Resource_Cart_Migrate extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_checkout/cart_migrate', 'entity_id');
    }
}
