<?php
/**
 * Customer model.
 * Overridden, because we need to send transactional emails from StrongMail service.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 * @method string getEmail()
 * @method string getFirstname()
 * @method string getLastname()
 * @method string getRpToken()
 */
class Tgc_StrongMail_Model_Customer extends Tgc_Customer_Model_Customer
{
    const MIN_PASSWORD_LENGTH = 5;
    const MAX_PASSWORD_LENGTH = 20;

    protected $_nameRequired = true;

    /**
     * Send email with reset password confirmation link.
     * The email will be sent through StrongMail service.
     *
     * @throws Mage_Core_Exception
     * @return Tgc_StrongMail_Model_Customer
     */
    public function sendPasswordResetConfirmationEmail()
    {
        $storeId = $this->getStoreId();
        if (!$storeId) {
            $storeId = $this->_getWebsiteStoreId();
        }

        $emailSender = Mage::getModel('tgc_strongMail/email_customer_resetPassword')
            ->setCustomer($this)
            ->setStoreId($storeId);

        try {
            $emailSender->send();
        } catch (Exception $e) {
            Mage::logException($e);
            throw new Mage_Core_Exception(
                Mage::helper('tgc_strongMail')->__(
                    'An error has been occurred during sending an e-mail. Please try again later or contact us.'
                )
            );
        }

        return $this;
    }

    /**
     * Send email with new account related information
     *
     * @param string $type
     * @param string $backUrl
     * @param string $storeId
     * @throws Mage_Core_Exception
     * @return Tgc_StrongMail_Model_Customer
     */
    public function sendNewAccountEmail($type = 'registered', $backUrl = '', $storeId = '0')
    {
        if (!$storeId) {
            $storeId = $this->_getWebsiteStoreId($this->getSendemailStoreId());
        }

        $emailSender = Mage::getModel('tgc_strongMail/email_customer_welcome')
            ->setType($type)
            ->setCustomer($this)
            ->setStoreId($storeId)
            ->setBackUrl($backUrl);

        try {
            $emailSender->send();
        } catch (Exception $e) {
            Mage::logException($e);
            throw new Mage_Core_Exception(
                Mage::helper('tgc_strongMail')->__(
                    'Your request could not be completed, because an error occurred while trying to send you an e-mail. Please try again later or contact us.'
                )
            );
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isNameRequired()
    {
        return $this->_nameRequired;
    }

    /**
     * @param bool $required
     * @return $this
     */
    public function setIsNameRequired($required)
    {
        $this->_nameRequired = $required;
        return $this;
    }

    /**
     * Override to change password validation per ticket #86
     * 8-20 characters
     * must contain 1 letter, 1 number and 1 special character
     */
    public function validate()
    {
        $errors = array();

        if ($this->isNameRequired()) {
            if (!Zend_Validate::is( trim($this->getFirstname()) , 'NotEmpty')) {
                $errors[] = Mage::helper('customer')->__('The first name cannot be empty');
            }

            if (!Zend_Validate::is( trim($this->getLastname()) , 'NotEmpty')) {
                $errors[] = Mage::helper('customer')->__('The last name cannot be empty');
            }
        }

        if (!Zend_Validate::is($this->getEmail(), 'EmailAddress')) {
            $errors[] = Mage::helper('customer')->__('Invalid email address "%s"', $this->getEmail());
        }

        $password = $this->getPassword();
        if (!$this->getId() && !Zend_Validate::is($password , 'NotEmpty')) {
            $errors[] = Mage::helper('customer')->__('The password cannot be empty');
        }
        if (strlen($password) && !Zend_Validate::is($password, 'StringLength', array(self::MIN_PASSWORD_LENGTH))) {
            $errors[] = Mage::helper('customer')->__('The minimum password length is %s', self::MIN_PASSWORD_LENGTH);
        }
        if (strlen($password) && strlen($password) > self::MAX_PASSWORD_LENGTH) {
            $errors[] = Mage::helper('customer')->__('The maximum password length is %s', self::MAX_PASSWORD_LENGTH);
        }
        if (strlen($password) && $this->_wasPasswordUsedPreviously($password)) {
            $errors[] = Mage::helper('customer')->__('You cannot use a password that you have previously used');
        }
        $confirmation = $this->getConfirmation();
        if ($password != $confirmation) {
            $errors[] = Mage::helper('customer')->__('Please make sure your passwords match');
        }

        $entityType = Mage::getSingleton('eav/config')->getEntityType('customer');
        $attribute = Mage::getModel('customer/attribute')->loadByCode($entityType, 'dob');
        if ($attribute->getIsRequired() && '' == trim($this->getDob())) {
            $errors[] = Mage::helper('customer')->__('The Date of Birth is required.');
        }
        $attribute = Mage::getModel('customer/attribute')->loadByCode($entityType, 'taxvat');
        if ($attribute->getIsRequired() && '' == trim($this->getTaxvat())) {
            $errors[] = Mage::helper('customer')->__('The TAX/VAT number is required.');
        }
        $attribute = Mage::getModel('customer/attribute')->loadByCode($entityType, 'gender');
        if ($attribute->getIsRequired() && '' == trim($this->getGender())) {
            $errors[] = Mage::helper('customer')->__('Gender is required.');
        }

        if (empty($errors)) {
            return true;
        }
        return $errors;
    }

    private function _wasPasswordUsedPreviously($password)
    {
        return false;
    }
}