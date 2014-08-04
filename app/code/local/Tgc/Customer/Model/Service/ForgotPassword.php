<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Customer_Model_Service_ForgotPassword
{
    public function send($email, $websiteId = null)
    {
        if (!Zend_Validate::is($email, 'EmailAddress')) {
            throw new Mage_Core_Exception('Invalid email address.');
        }

        /* @var $customer Mage_Customer_Model_Customer */
        $customer = Mage::getModel('customer/customer');

        if ($websiteId) {
            $customer->setWebsiteId($websiteId);
        }

        $customer->loadByEmail($email);

        if ($customer->getId()) {
            $newResetPasswordLinkToken = Mage::helper('customer')->generateResetPasswordLinkToken();
            $customer->changeResetPasswordLinkToken($newResetPasswordLinkToken);
            $customer->sendPasswordResetConfirmationEmail();
        }
    }
}