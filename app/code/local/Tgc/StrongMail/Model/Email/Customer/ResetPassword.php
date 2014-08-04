<?php
/**
 * Reset Password transactional email sender
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_StrongMail_Model_Email_Customer_ResetPassword extends Tgc_StrongMail_Model_Email_Customer_Abstract
{
    const XML_PATH_MAILING_NAME_RESET_PASSWORD = 'tgc_strongmail/customer/reset_password';
    const DEFAULT_FIRSTNAME = 'Valued';
    const DEFAULT_LASTNAME  = 'Customer';

    /**
     * Returns additional parameters for transactional email template in key-value style.
     * @todo review additional parameters for Reset Password email template, when the access to Message Studio will be granted.
     *
     * @return array
     */
    protected function _getAdditionalParams()
    {
        $data = array(
            'firstname'  => $this->_getFirstname(),
            'lastname'   => $this->_getLastname(),
            'token'      => $this->getCustomer()->getRpToken(),
            'email_addr' => $this->getCustomer()->getEmail(),
            'customerid' => $this->getCustomer()->getId()
        );
        return $data;
    }

    /**
     * @return string
     */
    protected function _getFirstname()
    {
        if ($this->getCustomer()->getFirstname()) {
            return $this->getCustomer()->getFirstname();
        } else {
            return self::DEFAULT_FIRSTNAME;
        }
    }

    /**
     * @return string
     */
    protected function _getLastname()
    {
        if ($this->getCustomer()->getLastname()) {
            return $this->getCustomer()->getLastname();
        } else {
            return self::DEFAULT_LASTNAME;
        }
    }

    /**
     * Gets mailing name
     *
     * @return string
     */
    protected function _getMailingName()
    {
        return Mage::getStoreConfig(self::XML_PATH_MAILING_NAME_RESET_PASSWORD, $this->getStoreId());
    }
}
