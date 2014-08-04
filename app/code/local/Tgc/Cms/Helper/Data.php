<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Helper_Data extends Mage_Core_Helper_Data
{
    private $_userType;

    /**
     * Truncate string to the last included word
     *
     * @param $text
     * @param $charactersAmount
     * @param string $suffix
     * @return string
     */
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
        $cookie = Mage::getModel('ninja/ninja')->getCookie(Tgc_Cookieninja_Model_Ninja::COOKIE_IS_PROSPECT);
        if ($cookie === '0' || $cookie === 0) {
            return true;
        }

        return false;
    }

    public function isProspect()
    {
        return $this->isAuthenticated() ? 0 : 1;
    }

    public function getUserType()
    {
        if (isset($this->_userType)) {
            return $this->_userType;
        }

        if ($this->isAuthenticated()) {
            $this->_userType = Tgc_Cms_Model_Source_UserType::LOGGED;
        } else {
            $this->_userType = Tgc_Cms_Model_Source_UserType::GUEST;
        }

        return $this->_userType;
    }
}
