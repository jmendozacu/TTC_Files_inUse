<?php
/**
 * Customer login
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Customer_Block_Form_Login extends Mage_Customer_Block_Form_Register
{
    const CACHE_TAG = 'HEADER_LOGIN_BLOCK';

    private $_username = -1;

    public function _construct()
    {
        parent::_construct();

        $this->addData(array('cache_lifetime' => null));
        $this->addCacheTag(array(
            self::CACHE_TAG,
        ));
    }

    public function getCacheKeyInfo()
    {
        $info = array(
            'customer'    => Mage::getSingleton('customer/session')->getCustomer()->getId(),
            'logged_in'   => Mage::getSingleton('customer/session')->isLoggedIn() ? 1 : 0,
            'is_prospect' => Mage::getSingleton('customer/session')->getCustomer()->getIsProspect(),
        );

        return array_merge($info, parent::getCacheKeyInfo());
    }

    protected function _prepareLayout()
    {
        return Mage_Core_Block_Template::_prepareLayout();
    }

    /**
     * Retrieve form posting url
     *
     * @return string
     */
    public function getLoginActionUrl()
    {
        return $this->helper('customer')->getLoginPostUrl();
    }

    /**
     * Retrieve create new account url
     *
     * @return string
     */
    public function getCreateAccountUrl()
    {
        $url = $this->getData('create_account_url');
        if (is_null($url)) {
            $url = $this->helper('customer')->getRegisterUrl();
        }
        return $url;
    }

    /**
     * Retrieve password forgotten url
     *
     * @return string
     */
    public function getForgotPasswordUrl()
    {
        return $this->helper('customer')->getForgotPasswordUrl();
    }

    /**
     * Retrieve username for form field
     *
     * @return string
     */
    public function getUsername()
    {
        if (-1 === $this->_username) {
            $this->_username = Mage::getSingleton('customer/session')->getUsername(true);
        }
        return $this->_username;
    }
}
