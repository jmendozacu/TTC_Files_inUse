<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_MobileApi_Model_Login extends Tgc_MobileApi_Model_Login_Abstract
{
    protected function _login($email, $password, $websiteCode = null)
    {
        try {
            if (empty($email)) {
                throw new Mage_Core_Exception('Please enter email addresss.');
            }
            if (empty($password)) {
                throw new Mage_Core_Exception('Please enter password.');
            }


            $session = Mage::getSingleton('tgc_customer/activeSession');

            if ($websiteCode) {
                $website = Mage::getModel('core/website')->load($websiteCode);
                if ($website->isObjectNew()) {
                    throw new InvalidArgumentException('Invalid website code.');
                }
                Mage::app()->getStore()->setWebsiteId($website->getId());
            }

            $session->login($email, $password);
            $response = $this->_createSuccessResponse($session->getCustomer());
        } catch (Exception $e) {
            Mage::logException($e);
            $response = $this->_createFailResponse($e);
        }

        return $response;
    }

    protected function _create(array $filteredData)
    {
        $params = $this->getRequest()->getBodyParams();
        if (!isset($params['website'])) {
            $params['website'] = null;
        }
        $this->_render(array(
            'LoginPostJsonResult' => $this->_login($params['email'], $params['password'], $params['website'])
        ));
    }

    public function dispatch()
    {
        parent::dispatch();

        $this->getResponse()->clearHeader('Location');

    }

    protected function _retrieve()
    {
        $email = $this->getRequest()->getParam('email');
        $password = $this->getRequest()->getParam('password');
        $websiteCode = $this->getRequest()->getParam('website');

        return $this->_login($email, $password, $websiteCode);
    }
}