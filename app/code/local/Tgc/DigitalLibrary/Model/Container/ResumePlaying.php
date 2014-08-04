<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/**
 * resume playing widget container
 */
class Tgc_DigitalLibrary_Model_Container_ResumePlaying
    extends Enterprise_PageCache_Model_Container_Abstract
{
    /**
     * Get identifier from cookies
     *
     * @return string
     */
    protected function _getIdentifier()
    {
        $identifier = $this->_getCookieValue(Tgc_DigitalLibrary_Block_ResumePlaying::COOKIE_NAME, '') . '_'
            . $this->_getCookieValue(Tgc_DigitalLibrary_Model_Resource_CrossPlatformResume::COOKIE_NAME, '') . '_'
            . $this->_getCookieValue(Mage_Core_Controller_Front_Action::SESSION_NAMESPACE, microtime());

        return $identifier;
    }

    /**
     * Get cache identifier
     *
     * @return string
     */
    protected function _getCacheId()
    {
        $cacheId = $this->_placeholder->getAttribute('cache_id');
        $identifier = 'DIGITAL_LIBRARY_RESUMEPLAYING_' . md5($cacheId . $this->_getIdentifier());

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
