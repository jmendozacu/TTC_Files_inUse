<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Lectures_Helper_Subscription_Abstract extends Mage_Core_Helper_Abstract
{
    protected $_customerToUnsubscribe;


    /**
     * Validates email to prevent erroneius values from being passed in through the url.
     * @param $email
     * @return null
     */
    public function validateUnsubscribeEmail($email = '', $key = '')
    {
        if($email) {
            if(!Zend_Validate::is($email,'EmailAddress')) {
                $email = null;
            }

            //If we needed to validate an encrypted parameter, isEmailKeyValid can be called here to enforce this validation.
            //this validation does not need to be called, but if it did, this would be the ONLY place it would need to be called.
        }

        return $email;
    }

    /**
     * Checks to see if the email address, when md5 sha1 is applied to it md5(sha1($email)), is the same as the key.
     *
     * @param string $email
     * @param string $key
     * @return bool
     */
    public function isEmailKeyValid($email = '', $key = '')
    {
        $isEmailKeyValid = false;

        if($email) {
            if(!$key) {
                $key = $this->getEmailKey(); //if key was not supplied in the argument, this pulls the value of key out of the request.
            }

            if($key) { //if a key is not supplied in the request, nor as an argument, isEmailKeyValid not be set to true.
                if($key == $this->encryptUnsubscribeEmail($email)) { //if decrypted key does not equal email then it is invalid!
                    $isEmailKeyValid = true;
                }
            }
        }

        return $isEmailKeyValid;
    }

    /**
     * Checks to see if a customer should be eligible to unsubscribe.  Only customers who are subsribed or unconfirmed are eligible to unsubscribe.
     * @return bool
     */
    public function isCustomerEligibleToUnsubscribe($email = '')
    {
        $isCustomerEligibleToUnsubscribe = false;

        $customerToUnsubscribe = $this->getCustomerToUnsubscribe($email);

        if($customerToUnsubscribe) {
            if(in_array($customerToUnsubscribe->getData('free_lect_subscribe_status'), array(1,2))) { //a users status must be either 1 (subscribed) or 2 (confirmed) in order to be able to unsubscribe.
                $isCustomerEligibleToUnsubscribe = true;
            }
        }

        return $isCustomerEligibleToUnsubscribe;
    }

    /**
     * Checks to see if the unsubscribe request is valid.
     * @param string $email
     * @param string $key
     * @return bool
     */
    public function isUnsubscribeRequestValid($email = 'request', $key = '')
    {
        $isUnsubscribeRequestValid = false;

        //Reason why we call getCustomerToUnsubscribe is because if a customer exists, the email is valid, and the email key is valid, it returns true.
        //this are exact same conditions that must be true for a request to be valid.
        $customerToUnsubscribe = $this->getCustomerToUnsubscribe($email, $key);
        if($customerToUnsubscribe) {
            $isUnsubscribeRequestValid = true;
        }

        return $isUnsubscribeRequestValid;
    }



    /**
     * Checks to see if user has been subscribed to free lectures.
     * @return bool
     */
    public function isSubscribed()
    {
        $isSubscribedFreelectures = false;

        $customerToUnsubscribe = $this->getCustomerToUnsubscribe();

        if($customerToUnsubscribe) {
            if(in_array($customerToUnsubscribe->getData('free_lect_subscribe_status'), array(1,2))) {
                $isSubscribedFreelectures = true;
            }
        }

        return $isSubscribedFreelectures;
    }

    /**
     * Checks to see if a customer is not confirmed.  Only free lecture customers with subscribe_status of 2 are unconfirmed.
     * @return bool
     */
    public function isNotConfirmed()
    {
        $isNotConfirmed = false;

        $customerToUnsubscribe = $this->getCustomerToUnsubscribe();
        if($customerToUnsubscribe) {
            if($customerToUnsubscribe->getData('free_lect_subscribe_status') == 2) {
                $isNotConfirmed = true;
            }
        }

        return $isNotConfirmed;
    }

    /**
     * Sets session variable marking user as having unsubscribed.  This makes it so that if a user unsubscribes, and the refreshes the page,
     * magento will understand that the user has unsubscribed.
     */
    public function markUserAsUnsubscribed()
    {
        Mage::getModel('core/session')->setUserHasBeenUnsubscrited(true);
    }

    /**
     * Retrieves the customer object of the user being unsubscribed.
     * @param string $email
     * @param string $key
     * @return mixed
     */
    public function getCustomerToUnsubscribe($email = 'request', $key = '')
    {
        if(is_null($this->_customerToUnsubscribe)) {
            if($email == 'request') {
                $email = $this->getEmailToUnsubcribe(); //pulls email from request object.
            }

            $email = $this->validateUnsubscribeEmail($email, $key);

            if($email) {
                $customer = Mage::getModel('customer/customer')->loadByEmail($email);
                if($customer->getId()) {
                    $this->_customerToUnsubscribe = $customer;
                }
            }
        }

        return $this->_customerToUnsubscribe;
    }

    /**
     * Checks to see if customer has unsubscribed.  If a user refreshes the page, function still understands
     * that user has unsubscribed even though there are no parameters in the url.  It can determine this using a session variable.
     * @return bool
     */
    public function hasUserBeenUnsubscrited()
    {
        $hasUserBeenUnsubscribed = false;
        $customerToUnsubscribe = $this->getCustomerToUnsubscribe();
        if($customerToUnsubscribe) {
            if($customerToUnsubscribe->getData('free_lect_subscribe_status') == 3) {
                $hasUserBeenUnsubscribed = true;
            }
        }

        if($this->getEmailAddressToUnsubscribe()) {
            $this->resetUserAsUnsubscrited();
        }

        if(Mage::getModel('core/session')->getUserHasBeenUnsubscrited()) {
            $hasUserBeenUnsubscribed = true;
        }

        return $hasUserBeenUnsubscribed;
    }

    /**
     * Encrypts the email the user has requested to unsubribe.
     * @param string $email
     * @return bool|string
     */
    public function encryptUnsubscribeEmail($email = '')
    {
        $encryptedEmail = false;

        if(!$email) {
            $email = $this->getEmailAddressToUnsubscribe();
        }

        if($email) {
            $encryptedEmail = md5(sha1($email));
        }

        return $encryptedEmail;
    }

    public function resetUserAsUnsubscrited()
    {
        Mage::getModel('core/session')->setUserHasBeenUnsubscrited(false);
    }

    /**
     * Retrieves email that user has requested to unsubscribe, and then validates it.
     * @return mixed
     */
    public function getEmailToUnsubcribe()
    {
        $emailRequested = $this->getEmailAddressToUnsubscribe();
        return $this->validateUnsubscribeEmail($emailRequested);
    }

    /**
     * Retrieves email address that user has requested needs to be unsubscribed.
     * @return mixed
     */
    public function getEmailAddressToUnsubscribe()
    {
        return Mage::app()->getRequest()->getParam('emailid');
    }

    /**
     * Retrieves the email key from the url.
     * @return mixed
     */
    public function getEmailKey()
    {
        return Mage::app()->getRequest()->getParam('key');
    }
}

