<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Model_PurchaseHistory extends Mage_Core_Model_Abstract
{
    const CACHE_TAG         = 'purchase_history';

    protected $_eventPrefix = 'purchase_history';
    protected $_eventObject = 'purchaseHistory';

    protected function _construct()
    {
        $this->_init('tgc_dl/purchaseHistory');
    }
}
