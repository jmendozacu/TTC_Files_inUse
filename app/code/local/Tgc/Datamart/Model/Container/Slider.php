<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/**
 * Slider widget container
 */
class Tgc_Datamart_Model_Container_Slider
    extends Enterprise_PageCache_Model_Container_Abstract
{
    /**
     * Get identifier from cookies
     *
     * @return string
     */
    protected function _getIdentifier()
    {
        $identifier = $this->_getCookieValue(Enterprise_PageCache_Model_Cookie::COOKIE_CUSTOMER, '') . '_'
            . $this->_getCookieValue(Enterprise_PageCache_Model_Cookie::COOKIE_CUSTOMER_LOGGED_IN, 0) . '_'
            . intval($this->_getCookieValue(Tgc_CookieNinja_Model_Ninja::COOKIE_IS_PROSPECT, 0));

        return $identifier;
    }

    /**
     * Get cache identifier
     *
     * @return string
     */
    protected function _getCacheId()
    {
        $cacheId    = $this->_placeholder->getAttribute('cache_id');
        $identifier = 'DATAMART_SLIDER_' . md5($cacheId . $this->_getIdentifier());

        return $identifier;
    }

    /**
     * Render block content
     *
     * @return string
     */
    protected function _renderBlock()
    {
        return $this->_getPlaceHolderBlock()->toHtml();
    }
}
