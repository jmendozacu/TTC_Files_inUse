<?php
/**
 * Customer Active Session helper
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Customer_Model_Cookie extends Mage_Core_Model_Cookie
{
    /**
     * Retrieve cookie lifetime
     *
     * @return int
     */
    public function getLifetime()
    {
        if (!is_null($this->_lifetime)) {
            $lifetime = $this->_lifetime;
        } else {
            if (isset($_COOKIE[Tgc_Customer_Model_ActiveSession::KEEP_LOGGED_COOKIE_NAME])
                && $_COOKIE[Tgc_Customer_Model_ActiveSession::KEEP_LOGGED_COOKIE_NAME] == 0) {

                $lifetime = Tgc_Customer_Model_ActiveSession::UNKEPT_SESSION_LIFETIME;
            } else {
                $lifetime = Mage::getStoreConfig(self::XML_PATH_COOKIE_LIFETIME, $this->getStore());
            }
        }
        if (!is_numeric($lifetime)) {
            $lifetime = 3600;
        }
        return $lifetime;
    }
}
