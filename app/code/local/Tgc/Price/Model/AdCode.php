<?php
/**
 * Ad code model
 *
 * @method int getCode() getCode() Returns ad code
 * @method int getCustomerGroupId() getCustomerGroupId() Returns customer group ID
 *  
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Price_Model_AdCode extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_price/adCode');
    }
}