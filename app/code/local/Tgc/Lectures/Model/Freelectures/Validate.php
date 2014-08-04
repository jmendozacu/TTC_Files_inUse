<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Lectures_Model_Freelectures_Validate extends Mage_Core_Model_Abstract
{
    protected $_freeLectureRequiredFields = array('email_address','password');

    protected $userFriendlyFieldNames = array(
      'email_address'       => 'Email Address',
      'password'            => 'Password',
      'password_confirm'    => 'Confirm Password',
    );

    public function validatePassword($formData, &$errors, &$isFormValid, $isExistingCustomer = false)
    {
        if($isFormValid) { //prevents check from running, unless all requried fields have been entered.
            if(!$isExistingCustomer) { //the input field called password_confirm is not visible to existing users, if user clicks 'Existing user' link, we should not validate it!
                if($formData['password'] != $formData['password_confirm']) {
                    $isFormValid = false;
                    $errors[] = Mage::helper('lectures')->__("The passwords do not match.");
                }
            }

            if(strlen($formData['password']) < 5) {
                $errors[] = Mage::helper('lectures')->__('The minimum password length is %s', 5);
                $isFormValid = false;
            }
        }
    }

    public function validateEmail($formData, &$errors, &$isFormValid, $isExistingCustomer = false)
    {
        if($isFormValid) {
            if(!$isExistingCustomer) { //this runs if a person is signing up for first time, it needs check for duplicates// if customer is logging in, it does not need to be run.
                if(!Zend_Validate::is($formData['email_address'], 'EmailAddress')) {
                    $errors[] = Mage::helper('lectures')->__('The email entered is not valid.');
                    $isFormValid = false;
                }
            }
        }
    }

    public function validateRequiredFields($formData, &$errors, &$isFormValid, $exceptions = array()) {
        $missingRequiredFields = array();
        foreach($this->_freeLectureRequiredFields as $fieldname) {
            if(!isset($formData[$fieldname]) || !$formData[$fieldname]) {
                $missingRequiredFields[$fieldname] = $this->userFriendlyFieldNames[$fieldname];
            }
        }

        if(count($exceptions) > 0) {
            foreach($exceptions as $exceptionFieldName) {
                if(isset($missingRequiredFields[$exceptionFieldName])) {
                    unset($missingRequiredFields[$exceptionFieldName]);
                }
            }
        }

        if(count($missingRequiredFields) > 0) {
            $errors[] = Mage::helper('lectures')->__('The following required field(s) are missing: ' . implode(', ', $missingRequiredFields));
            $isFormValid = false;
        }
    }

    public function formatErrors($errors)
    {
        $errorsList = '';
        foreach($errors as $error) {
            $errorsList .= $error . "<br />";
        }

        return $errorsList;
    }
}