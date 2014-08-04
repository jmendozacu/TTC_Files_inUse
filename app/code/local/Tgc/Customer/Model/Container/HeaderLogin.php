<?php
/**
 * Customer
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/**
 * Header login container
 */
class Tgc_Customer_Model_Container_HeaderLogin
    extends Enterprise_PageCache_Model_Container_Abstract
{
    /**
     * Get identifier from cookies
     *
     * @return string
     */
    protected function _getIdentifier()
    {
        $identifier = $this->_getCookieValue(Mage_Core_Controller_Front_Action::SESSION_NAMESPACE, microtime()) . '_'
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
        $identifier = 'HEADER_LOGINFORM_' . md5($this->_placeholder->getAttribute('cache_id')
            . $this->_getIdentifier());

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
