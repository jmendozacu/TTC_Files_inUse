<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Model_MergeAccounts extends Mage_Core_Model_Abstract
{
    const CACHE_TAG         = 'merge_accounts';

    protected $_eventPrefix = 'merge_accounts';
    protected $_eventObject = 'mergeAccounts';

    protected function _construct()
    {
        $this->_init('tgc_dl/mergeAccounts');
    }
}
