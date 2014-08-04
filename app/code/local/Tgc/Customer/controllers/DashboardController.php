<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Customer_DashboardController extends Mage_Core_Controller_Front_Action
{
    /**
     * Retrieve customer session model object
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * Save username action
     */
    public function saveUsernameAction()
    {
        $result = array('success' => false, 'errors' => array());

        $customerSession = $this->_getSession();
        if (!$customerSession->isLoggedIn()) {
            $result['errors'][] = $this->__('Please login');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            return;
        }

        $username = $this->getRequest()->getParam('username');
        if (!$username) {
            $result['errors'][] = $this->__('Username is required');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            return;
        }

        try {
            $customer = $customerSession->getCustomer();
            $usernameAttribute = $customer->getAttribute('username');
            $dataModel = Mage_Eav_Model_Attribute_Data::factory($usernameAttribute, $customer);
            $dataModel->setIsAjaxRequest(true);
            $validationResult = $dataModel->validateValue($username);
            if ($validationResult !== true) {
                $result['errors'] = $validationResult;
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                return;
            }

            $customer->setUsername($username)
                ->save();
            $result['success'] = true;
            $result['success_msg'] = $this->__('You\'ve added a unique community username!');
        } catch (Mage_Core_Exception $e) {
            $result['errors'][] = $e->getMessage();
        } catch (Exception $e) {
            $result['errors'][] = $this->__('Cannot save the Username');
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Save username action
     */
    public function saveEmailAction()
    {
        $result = array('success' => false, 'errors' => array());

        $customerSession = $this->_getSession();
        if (!$customerSession->isLoggedIn()) {
            $result['errors'][] = $this->__('Please login');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            return;
        }

        $oldEmail = $this->getRequest()->getParam('old_email');
        if (!$oldEmail) {
            $result['errors'][] = $this->__('Old email address is required');
        } else if (!Zend_Validate::is($oldEmail, 'EmailAddress')) {
            $result['errors'][] = $this->__('Invalid old email address');
        }

        $newEmail = $this->getRequest()->getParam('email');
        if (!$newEmail) {
            $result['errors'][] = $this->__('New email address is required');
        } else if (!Zend_Validate::is($newEmail, 'EmailAddress')) {
            $result['errors'][] = $this->__('Invalid new email address');
        }

        $newEmailConfirm = $this->getRequest()->getParam('email_confirm');
        if (!$newEmailConfirm) {
            $result['errors'][] = $this->__('New email address confirmation is required');
        } else if ($newEmail != $newEmailConfirm) {
            $result['errors'][] = $this->__('New email address and new email address do not match');
        }

        if ($result['errors']) {
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            return;
        }

        $customer = $customerSession->getCustomer();
        if (strtolower($customer->getEmail()) != strtolower($oldEmail)) {
            $result['errors'][] = $this->__('Invalid old email address');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            return;
        }

        try {
            $emailAttribute = $customer->getAttribute('email');
            $dataModel = Mage_Eav_Model_Attribute_Data::factory($emailAttribute, $customer);
            $dataModel->setIsAjaxRequest(true);
            $validationResult = $dataModel->validateValue($newEmail);
            if ($validationResult !== true) {
                $result['errors'] = $validationResult;
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                return;
            }

            $customer->setEmail($newEmail)
                ->save();
            $result['success'] = true;
            $result['success_msg'] = $this->__('You\'ve updated your email address!');
        } catch (Mage_Core_Exception $e) {
            $result['errors'][] = $e->getMessage();
        } catch (Exception $e) {
            $result['errors'][] = $this->__('Cannot save email address');
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Save password action
     */
    public function savePasswordAction()
    {
        $result = array('success' => false, 'errors' => array());

        $customerSession = $this->_getSession();
        if (!$customerSession->isLoggedIn()) {
            $result['errors'][] = $this->__('Please login');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            return;
        }

        $currPass = $this->getRequest()->getParam('current_password');
        if (!$currPass) {
            $result['errors'][] = $this->__('Current password is required');
        }

        $newPass = $this->getRequest()->getParam('password');
        if (strlen($newPass) && !Zend_Validate::is($newPass, 'StringLength', array(5))) {
            $result['errors'][] = Mage::helper('customer')->__('The minimum password length is %s', 5);
        }

        $confPass = $this->getRequest()->getParam('confirmation');
        if (!$confPass) {
            $result['errors'][] = $this->__('New password confirmation is required');
        } else if ($confPass != $newPass) {
            $result['errors'][] = $this->__('Please make sure your passwords match');
        }

        $customer = $customerSession->getCustomer();
        $oldPass = $this->_getSession()->getCustomer()->getPasswordHash();
        if (Mage::helper('core/string')->strpos($oldPass, ':')) {
            list($_salt, $salt) = explode(':', $oldPass);
        } else {
            $salt = false;
        }

        if($currPass && $newPass && $currPass == $newPass && $customer->hashPassword($currPass, $salt) == $oldPass) {
            $result['errors'][] = $this->__('The new password cannot be the same as the old password.');
        }

        if ($result['errors']) {
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            return;
        }

        if ($customer->hashPassword($currPass, $salt) != $oldPass) {
            $result['errors'][] = $this->__('Invalid current password');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            return;
        }

        try {
            $customer->setPassword($newPass)
                ->save();
            $result['success'] = true;
            $result['success_msg'] = $this->__('You\'ve updated your password!');
        } catch (Mage_Core_Exception $e) {
            $result['errors'][] = $e->getMessage();
        } catch (Exception $e) {
            $result['errors'][] = $this->__('Cannot save password');
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Save address action
     */
    public function saveAddressAction()
    {
        $result = array('success' => false, 'errors' => array());

        $customerSession = $this->_getSession();
        if (!$customerSession->isLoggedIn()) {
            $result['errors'][] = $this->__('Please login');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            return;
        }

        $customer = $this->_getSession()->getCustomer();
        /* @var $address Mage_Customer_Model_Address */
        $address  = Mage::getModel('customer/address');
        $addressId = $this->getRequest()->getParam('address_id');
        if ($addressId) {
            $existsAddress = $customer->getAddressById($addressId);
            if ($existsAddress->getId() && $existsAddress->getCustomerId() == $customer->getId()) {
                $address->setId($existsAddress->getId());
            }
        }

        $errors = array();

        /* @var $addressForm Mage_Customer_Model_Form */
        $addressForm = Mage::getModel('customer/form');
        $addressForm->setFormCode('customer_address_edit')
            ->setEntity($address);
        $addressData    = $addressForm->extractData($this->getRequest());
        $addressErrors  = $addressForm->validateData($addressData);
        if ($addressErrors !== true) {
            $errors = $addressErrors;
        }

        try {
            $addressForm->compactData($addressData);
            $address->setCustomerId($customer->getId())
                ->setIsDefaultBilling($this->getRequest()->getParam('default_billing', false))
                ->setIsDefaultShipping($this->getRequest()->getParam('default_shipping', false));

            $addressErrors = $address->validate();
            if ($addressErrors !== true) {
                $errors = array_merge($errors, $addressErrors);
            }

            if (count($errors) === 0) {
                $address->save();
                $result['success'] = true;
                $result['success_msg'] = $this->__('The address has been saved');
                $additionalAddressData = array(
                    'id'                  => $address->getId(),
                    'is_default_billing'  => $address->getIsDefaultBilling()
                        || (($defaultBillingAddress = $customer->getPrimaryBillingAddress())
                            && $address->getId() == $defaultBillingAddress->getId()),
                    'is_default_shipping' => $address->getIsDefaultShipping()
                        || (($defaultShippingAddress = $customer->getPrimaryShippingAddress())
                            && $address->getId() == $defaultShippingAddress->getId())
                );
                $result['address_data'] = $additionalAddressData
                    + Mage::helper('tgc_customer/address')->prepareAttributesForOutput($address);
            } else {
                $result['errors'] = $errors;
            }
        } catch (Mage_Core_Exception $e) {
            $result['errors'][] = $e->getMessage();
        } catch (Exception $e) {
            $result['errors'][] = $this->__('Cannot save address');
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Delete address action
     */
    public function deleteAddressAction()
    {
        $result = array('success' => false, 'errors' => array());

        $customerSession = $this->_getSession();
        if (!$customerSession->isLoggedIn()) {
            $result['errors'][] = $this->__('Please login');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            return;
        }

        $customer = $this->_getSession()->getCustomer();
        /* @var $address Mage_Customer_Model_Address */
        $address  = Mage::getModel('customer/address');
        $addressId = $this->getRequest()->getParam('address_id');
        if ($addressId) {
            $existsAddress = $customer->getAddressById($addressId);
            if ($existsAddress->getId() && $existsAddress->getCustomerId() == $customer->getId()) {
                $address->setId($existsAddress->getId());
            }
        }

        if (!$address->getId()) {
            $result['errors'][] = $this->__('Address not found');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            return;
        }

        try {
            $address->delete();
            $result['success'] = true;
            $result['address_id'] = $existsAddress->getId();
        } catch (Mage_Core_Exception $e) {
            $result['errors'][] = $e->getMessage();
        } catch (Exception $e) {
            $result['errors'][] = $this->__('Cannot delete address');
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
}
