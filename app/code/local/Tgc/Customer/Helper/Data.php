<?php
/**
 * Customer helper
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Customer_Helper_Data extends Mage_Customer_Helper_Data
{
    const FREE_LECTURES_CONFIRMATION_PARAMETER_ID = 'CustomerID';
    const FREE_LECTURES_CONFIRMATION_PARAMETER_TOKEN = 'Token';

    public function getLoggedInHeaderHtml()
    {
        $maxUserLen = 16;
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $username = $customer->getName();

        //Free lecture prospects might not have a username, because they are not asked to enter their first and last
        //name when creating a new account.  Therefore, we will grab their email instead.
        if(!trim($username)) {
            $username = $customer->getEmail();
        }

        if (strlen($username) > $maxUserLen) {
            $username = substr($username,0,$maxUserLen) . '...';
        }

        $block = Mage::getModel('cms/block')->load('logged-in-user-dropdown');
        $customerAccountUrl = Mage::helper('customer')->getAccountUrl();

        $html = '<div class="sign-container signed-in-container f-right nav-arrow-down">'
            . '<div class="clearer">'
                . '<span class="sign-in grid12-12 text-header-nav-medium signed-in username-account-js"><a href="' . $customerAccountUrl . '" class="nav-text-link-login">' . $username . '</a></span>'
                . Mage::helper('cms')->getBlockTemplateProcessor()->filter($block->getContent())
            . '</div>'
        . '</div>';

        return $html;
    }

    public function getLoggedOutHeaderHtml()
    {

        $html = '<div class="sign-container signed-out-container f-right nav-arrow-down">'
                . '<div class="clearer">'
                   . '<span class="sign-in grid12-12 text-header-nav-big signed-out"><span class="nav-text-link-login">' .$this->__('Sign In') . '</span></span>'
                . '</div>'
            . '</div>';

        return $html;
    }

    /**
     * Generate web_user_id value
     *
     * @param array|string $userData
     * @return string
     */
    public function generateWebUserId($userData = null)
    {
        if (is_array($userData)) {
            $userData = implode('', $userData);
        }
        if (!$userData) {
            $userData = microtime(true) . mt_rand();
        }

        $hash = strtoupper(md5($userData));

        return substr($hash, 0, 8) . '-' . substr($hash, 8, 4) . '-' . substr($hash, 12, 4) . '-'
            . substr($hash, 16, 4) . '-' . substr($hash, 20);
    }

    /**
     * is current admin user an Administrator user?
     * @return bool
     */
    public function isAdministratorUser()
    {
        $adminUser = Mage::getSingleton('admin/session')->getUser();
        if ($adminUser && $adminUser->getId()) {
            $adminRole = $adminUser->getRole();
            return $adminRole->getRoleName() == 'Administrators';
        }
        return false;
    }

    public function isFreeLectureProspect()
    {
        $isFreeLectureProspect = false;
        $customer = Mage::helper('customer')->getCustomer();
        if($customer->getId()) {
            if($customer->getIsFreeLectureProspect()) {
                $isFreeLectureProspect = true;
            }
        }

        return $isFreeLectureProspect;
    }

    public function getUserType() {
        $session = Mage::getSingleton('customer/session');
        if (!$session->isLoggedIn()) {
            return Tgc_Cms_Model_Source_UserType::GUEST;
        }

        $customer = $session->getCustomer();
        if ($customer->getIsProspect()) {
            return Tgc_Cms_Model_Source_UserType::GUEST;
        }

        return Tgc_Cms_Model_Source_UserType::LOGGED;
    }

    public function getAttributeBestSellerByUserType() {
        $userType = $this->getUserType();

        $attribute = "";

        switch ($userType) {
            case Tgc_Cms_Model_Source_UserType::GUEST:
                $attribute = Tgc_Cms_Block_BestSellers::GUEST_ATTRIBUTE;
                break;
            case Tgc_Cms_Model_Source_UserType::LOGGED:
                $attribute = Tgc_Cms_Block_BestSellers::AUTHENTICATED_ATTRIBUTE;
                break;
        }

        return $attribute;
    }

    public function getFreeLecturesConfirmationParameterId()
    {
        return self::FREE_LECTURES_CONFIRMATION_PARAMETER_ID;
    }

    public function getFreeLecturesConfirmationParameterToken()
    {
        return self::FREE_LECTURES_CONFIRMATION_PARAMETER_TOKEN;
    }

    public function getAccountUrl()
    {
        return $this->_getUrl('account/info');
    }
}
