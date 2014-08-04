<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_MobileApi_Model_FreeLecturesSignup extends Tgc_MobileApi_Model_Resource
{
    protected function _retrieve()
    {
        $response  = false;
        $email     = $this->getRequest()->getParam('email');
        $password  = $this->getRequest()->getParam('password');
        $firstName = $this->getRequest()->getParam('firstName');
        $lastName  = $this->getRequest()->getParam('lastName');
        $adCode    = $this->getRequest()->getParam('adcode');
        $userAgent = $this->getRequest()->getParam('userAgent');
        $helper    = Mage::helper('tgc_catalog/freemarketinglecture');

        try {
            $customer = $this->_loadCustomer($email, $password, $firstName, $lastName);
            $response = $helper->signupCustomerFreelecture($customer, $adCode, $userAgent);
        } catch (Exception $e) {
            Mage::logException($e);
        }

        return array('response' => $response);
    }

    protected function _render($data)
    {
        $data = current($data);
        return parent::_render($data);
    }

    /**
     * Load customer by email and password creates it if does not exist
     *
     * @param string $email
     * @param string $password
     * @return Mage_Customer_Model_Customer
     */
    private function _loadCustomer($email, $password, $firstName, $lastName)
    {
        /* @var $customer Mage_Customer_Model_Customer */
        $customer = Mage::getModel('customer/customer')->loadByEmail($email);

        if ($customer->isObjectNew()) {
            $customer->setEmail($email)
                     ->setFirstname($firstName)
                     ->setLastname($lastName)
                     ->setPassword($password)
                     ->setConfirmation($password)
                     ->save();
            $customer->setIsAccountAtSignup(false);
        } else {
            $customer->authenticate($email, $password);
            $customer->setIsAccountAtSignup(true);
        }

        return $customer;
    }
}
