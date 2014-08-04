<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Model_Partners extends Mage_Core_Model_Abstract
{
    const CACHE_TAG         = 'partners';

    protected $_eventPrefix = 'partners';
    protected $_eventObject = 'partners';

    protected function _construct()
    {
        $this->_init('tgc_cms/partners');
    }
}
