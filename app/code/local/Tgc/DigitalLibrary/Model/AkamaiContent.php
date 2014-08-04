<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Model_AkamaiContent extends Mage_Core_Model_Abstract
{
    const CACHE_TAG         = 'akamai_content';

    protected $_eventPrefix = 'akamai_content';
    protected $_eventObject = 'akamaiContent';

    protected function _construct()
    {
        $this->_init('tgc_dl/akamaiContent');
    }
}
