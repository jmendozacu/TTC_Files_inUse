<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Model_Quotes extends Mage_Core_Model_Abstract
{
    const CACHE_TAG         = 'quotes';

    protected $_eventPrefix = 'quotes';
    protected $_eventObject = 'quotes';

    protected function _construct()
    {
        $this->_init('tgc_cms/quotes');
    }
}
