<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Model_AccessRights extends Mage_Core_Model_Abstract
{
    const CACHE_TAG         = 'access_rights';

    protected $_eventPrefix = 'access_rights';
    protected $_eventObject = 'accessRights';

    protected function _construct()
    {
        $this->_init('tgc_dl/accessRights');
    }
}
