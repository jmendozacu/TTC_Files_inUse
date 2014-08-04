<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Helper_Data extends Mage_Core_Helper_Data
{
    public function getBestsellerUrl()
    {
        return Mage::getUrl();
    }

    private $_userType;

    public function truncateTextByWords($text, $charactersAmount, $suffix = '')
    {
        $text = strip_tags($text);
        if (strlen($text) <= $charactersAmount) {
            return $text;
        }
        $truncatedText = substr($text . ' ', 0, $charactersAmount);
        return preg_replace('/\s+?(\S+)?$/', '', $truncatedText) . $suffix;
    }

    public function isAuthenticated()
    {
        $cookie = Mage::getModel('core/cookie')->get(Tgc_CookieNinja_Model_Ninja::COOKIE_IS_PROSPECT);
        if ($cookie === false || $cookie == 1) {
            return false;
        }

        return true;
    }

    public function getUserType()
    {
        if (isset($this->_userType)) {
            return $this->_userType;
        }

        if ($this->isAuthenticated()) {
            $this->_userType = Tgc_Boutique_Model_Source_UserType::LOGGED;
            return $this->_userType;
        }
        $session = Mage::getSingleton('customer/session');
        if (!$session->isLoggedIn()) {
            $this->_userType = Tgc_Boutique_Model_Source_UserType::GUEST;
            return $this->_userType;
        }

        $customer = $session->getCustomer();
        if ($customer->getIsProspect()) {
            $this->_userType = Tgc_Boutique_Model_Source_UserType::GUEST;
            return $this->_userType;
        }

        $this->_userType = Tgc_Boutique_Model_Source_UserType::LOGGED;

        return $this->_userType;
    }
}
