<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/**
 * Homepage category widget container
 */
class Tgc_Cms_Model_Container_HomepageCategory
    extends Enterprise_PageCache_Model_Container_Abstract
{
    /**
     * Get identifier from cookies
     *
     * @return string
     */
    protected function _getIdentifier()
    {
        $isLogged = $this->_getCookieValue(Enterprise_PageCache_Model_Cookie::COOKIE_CUSTOMER_LOGGED_IN, '');
        $value = empty($isLogged) ? 0 : 1;
        $identifier = $value . '_' . intval($this->_getCookieValue(Tgc_CookieNinja_Model_Ninja::COOKIE_IS_PROSPECT, 0)) . '_'
            . $this->_getCookieValue(Enterprise_PageCache_Model_Cookie::COOKIE_CUSTOMER, '');

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
        $identifier = 'HOMEPAGE_CATEGORY_' . md5($cacheId . $this->_getIdentifier());

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
