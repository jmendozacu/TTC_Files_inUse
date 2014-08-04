<?php
/**
 * Observer
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Customer_Model_Observer
{

    public function addActiveSessionLoginJs($observer)
    {
        if (Mage::helper('tgc_checkout')->isPaymentBridgeContext()) {
            return;
        }

        $response = $observer->getResponse();
        $html = Mage::app()->getLayout()->createBlock('core/template')->setTemplate('page/html/footer_js.phtml')->toHtml();

        $response->setBody(
            str_replace('</body>', $html. '</body>', $response->getBody(false))
        );
    }

    /**
     * Generate web user id for new customers
     *
     * @param Varien_Event_Observer $observer
     * @return \Tgc_Customer_Model_Observer
     */
    public function setWebUserIdBeforeCustomerSave(Varien_Event_Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();

        if ($customer && !$customer->getId() && !$customer->getWebUserId()) {
            $customerResourceModel = Mage::getModel('customer/customer')->getResource();
            $customerReadAdapter = Mage::getSingleton('core/resource')->getConnection('customer_write');
            $duplicateWebUserIdCheckSelect = $customerReadAdapter->select()
                ->from($customerResourceModel->getEntityTable(), array($customerResourceModel->getEntityIdField()))
                ->where('web_user_id = :web_user_id');
            $customerHelper = Mage::helper('tgc_customer');
            $webUserId = $customerHelper->generateWebUserId();
            $tries = 0;
            $triesLimit = 100;
            while ($customerReadAdapter->fetchOne($duplicateWebUserIdCheckSelect, array('web_user_id' => $webUserId))
                && $tries < $triesLimit) {
                $tries++;
                $webUserId = $customerHelper->generateWebUserId(array(
                    $customer->getEmail(),
                    microtime(true),
                    mt_rand()
                ));
            }
            if ($tries >= $triesLimit) {
                Mage::log('Unable to generate customer web user id - hit tries limit');
            } else {
                $customer->setWebUserId($webUserId);
            }
        }

        return $this;
    }

    /**
     * Check that web user id is unique
     *
     * @param Varien_Event_Observer $observer
     * @return \Tgc_Customer_Model_Observer
     */
    public function validateWebUserId(Varien_Event_Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        if ($customer && ($customer->getWebUserId() || $customer->getId())) {
            $customerResourceModel = Mage::getModel('customer/customer')->getResource();
            $customerReadAdapter = Mage::getSingleton('core/resource')->getConnection('customer_write');
            $duplicateWebUserIdCheckSelect = $customerReadAdapter->select()
                ->from($customerResourceModel->getEntityTable(), array($customerResourceModel->getEntityIdField()))
                ->where('web_user_id = :web_user_id');

            $bind = array('web_user_id' => $customer->getWebUserId());
            if ($customer->getId()) {
                $bind['customer_id'] = $customer->getId();
                $duplicateWebUserIdCheckSelect->where($customerResourceModel->getEntityIdField() . ' != :customer_id');
            }
            $webUserIdExists = $customerReadAdapter->fetchOne($duplicateWebUserIdCheckSelect, $bind);
            if ($webUserIdExists) {
                Mage::throwException(Mage::helper('tgc_customer')->__('The customer with the same Web User Id already exists'));
            }
        }

        return $this;
    }

    /**
     * Check that username is unique
     *
     * @param Varien_Event_Observer $observer
     * @return \Tgc_Customer_Model_Observer
     */
    public function validateUsername(Varien_Event_Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        if ($customer && $customer->getUsername()) {
            $customerResourceModel = Mage::getModel('customer/customer')->getResource();
            $customerReadAdapter = Mage::getSingleton('core/resource')->getConnection('customer_write');
            $duplicateUsernameCheckSelect = $customerReadAdapter->select()
                ->from($customerResourceModel->getEntityTable(), array($customerResourceModel->getEntityIdField()))
                ->where('username = :username');

            $bind = array('username' => $customer->getUsername());
            if ($customer->getId()) {
                $bind['customer_id'] = $customer->getId();
                $duplicateUsernameCheckSelect->where($customerResourceModel->getEntityIdField() . ' != :customer_id');
            }
            if ($customer->getSharingConfig()->isWebsiteScope()) {
                $bind['website_id'] = (int)$customer->getWebsiteId();
                $duplicateUsernameCheckSelect->where('website_id = :website_id');
            }
            $webUserIdExists = $customerReadAdapter->fetchOne($duplicateUsernameCheckSelect, $bind);
            if ($webUserIdExists) {
                Mage::throwException(Mage::helper('tgc_customer')->__('The customer with the same Username already exists'));
            }
        }

        return $this;
    }

    public function manageAdcode(Varien_Event_Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        $helper = Mage::helper('ninja/ninja');
        $session = Mage::getSingleton('customer/session');
        $ninja = Mage::getModel('ninja/ninja');

        $isNewCustomer = (bool)$ninja->isCustomerNew();
        $sessionAdCode = $this->_getSessionAdcode();
        $savedAdcode = $customer->getAdcode();
        $sessionIsDefault = $sessionAdCode ? (bool)$helper->isAdCodeDefault($sessionAdCode) : true;
        $savedIsDefault = (bool)$helper->isAdCodeDefault($savedAdcode);
        $ninja->unsetNewUserCookie();

        //customer is new, they have applied an adcode
        if ($isNewCustomer && !$sessionIsDefault) {
            $this->_saveCustomerAdcode($sessionAdCode, time() + $ninja::FOUR_DAYS);
            return;
        }
        //customer has a non-default adcode applied, it is not whatever is currently saved
        if (!$sessionIsDefault && $savedAdcode != $sessionAdCode) {
            $this->_saveCustomerAdcode($sessionAdCode);
            return;
        }
        //customer has a non-default adcode, it matches saved value
        if (!$sessionIsDefault) {
            if (!$customer->getAdcodeExpires()) {
                $this->_saveCustomerAdcode($sessionAdCode);
                return;
            }

            $this->_checkIfAdcodeExpired();
            return;
        }
        //check if customer has a saved adcode, try to apply it
        if ($savedAdcode) {
            $expired = $this->_checkIfAdcodeExpired();
            if ($expired || $savedIsDefault) {
                return;
            }

            if ($helper->validateAdcode($savedAdcode)) {
                $session->unsAdCode()
                    ->setCustomerGroupId(0)
                    ->unsAdCodeCustomerGroupId();
                $ninja->deleteCookie($ninja::COOKIE_AD_CODE);
                Mage::helper('checkout/cart')->getQuote()->setCustomerGroupId(0)->save();
                Mage::getModel('tgc_price/adCode_processor')->changePrices($savedAdcode);
                return;
            }
        }

        $this->_expireAdcode();
    }

    protected function _getSessionAdcode()
    {
        $session = Mage::getSingleton('customer/session');

        if ($session->getAdCode()) {
            return $session->getAdCode();
        }

        $ninja = Mage::getModel('ninja/ninja');

        return $ninja->getCookie($ninja::COOKIE_AD_CODE);
    }

    protected function _saveCustomerAdcode($code, $expires = null)
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();

        $customer->setAdcode($code);
        $customer->setAdcodeExpires($expires);
        try {
            $customer->save();
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    protected function _checkIfAdcodeExpired()
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $expires = $customer->getAdcodeExpires();

        if (empty($expires) || $expires > time()) {
            return false;
        }

        $this->_expireAdcode();
        return true;
    }

    protected function _expireAdcode()
    {
        $helper = Mage::helper('ninja/ninja');
        $session = Mage::getSingleton('customer/session');
        $ninja = Mage::getModel('ninja/ninja');
        $customer = $session->getCustomer();

        $defaultAdcode = $helper->getDefaultAdcode();
        try {
            $customer->setAdcode($defaultAdcode)
                ->setAdcodeExpires(null)
                ->setCustomerGroupId(0)
                ->save();
        } catch (Exception $e) {
            //catch likely fk constraint exception during save
            $message = Mage::helper('tgc_customer')->__(
                'An error occurred saving adcode %s to customer. It appears you do no have all of the default adcodes setup. Shame on you.', $defaultAdcode
            );
            Mage::log($message);
            Mage::logException($e);
            return;
        }
        $session->unsAdCode()
            ->setCustomerGroupId(0)
            ->unsAdCodeCustomerGroupId();
        $ninja->deleteCookie($ninja::COOKIE_AD_CODE);
        Mage::helper('checkout/cart')->getQuote()->setCustomerGroupId(0)->save();
        Mage::getModel('tgc_price/adCode_processor')->changePrices($defaultAdcode);
    }
}
