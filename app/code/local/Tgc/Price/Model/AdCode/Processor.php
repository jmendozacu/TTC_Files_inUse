<?php
/**
 * Ad code processor
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Price_Model_AdCode_Processor
{
    /**
     * Changes prices by ad code
     *
     * Changes prices in catalog by setting appropriate customer group.
     * If ad code is undefined it will try to load it from session.
     * Saves ad code to session
     *
     * @param Tgc_Price_Model_AdCode|int $adCode Ad code
     */
    public function changePrices($adCode)
    {
        if (!$adCode instanceof Tgc_Price_Model_AdCode) {
            $adCode = Mage::getModel('tgc_price/adCode')->load($adCode);
        }
        if (!$adCode->isObjectNew() && $this->_validateCustomerGroupId($adCode->getCustomerGroupId())) {
            $this->_setCustomerGroupId($adCode->getCustomerGroupId());
            $this->_saveAdCode($adCode);
        } else {
            $defaultAdcode = Mage::helper('ninja/ninja')->getDefaultAdcode();
            $adCode->load($defaultAdcode);
            $adCode->setCode($defaultAdcode);
        }
        Mage::dispatchEvent('adcode_save_after', array('adcode' => $adCode));
    }

    /**
     * Resets pricing to default
     */
    public function resetPrices()
    {
        $this->_setCustomerGroupId($this->_getCustomerSession()->getCustomer()->getGroupId());
        $this->_getCustomerSession()->setAdCodeCustomerGroupId(null);
        $this->_saveAdCode(null);
    }

    /**
     * Returns current ad code
     *
     * @return string
     */
    public function getCurrentAdCode()
    {
        return $this->_getCustomerSession()->getAdCode();
    }

    /**
     * Returns true if current ad code is set
     *
     * @return boolean
     */
    public function isAdCodeActive()
    {
        return (bool)$this->getCurrentAdCode();
    }

    /**
     * Changes prices by ad code if it's associated with URL
     *
     * @param Varien_Event_Observer $observer
     */
    public function processRedirect(Varien_Event_Observer $observer)
    {
        //$request = $observer->getEvent()->getControllerAction()->getRequest();
        //$requestPath = trim($request->getOriginalPathInfo(),'/');

        // Helper function Checks to see if a URL Rewrite exists for the request. If it does, and it is valid, it returns the ad code
        $adCode = Mage::helper('adcoderouter')->retrieveValidAdCode();

        if ($adCode) {
            $this->changePrices($adCode);
        }
    }

    /**
     * Sets customer group ID by session
     *
     * @param Varien_Event_Observer $observer
     */
    public function setCustomerGroupIdByCurrentAdCode(Varien_Event_Observer $observer)
    {
        $customerGroupId = $this->_getCustomerSession()->getAdCodeCustomerGroupId();
        if ($customerGroupId) {
            $this->_setCustomerGroupId($customerGroupId);
        }
    }

    /**
     * Adds customer group ID by ad code to quote on copy
     *
     * @param Varien_Event_Observer $observer
     * @throws InvalidArgumentException If target of event is not quote model
     */
    public function addCustomerGroupIdToQuoteOnCopyByAdCode(Varien_Event_Observer $observer)
    {
        $customerGroupId = $this->_getCustomerSession()->getAdCodeCustomerGroupId();
        if ($customerGroupId) {
            if (!$observer->getEvent()->hasTarget()) {
                throw new InvalidArgumentException('Event does not have target.');
            }
            if (!$observer->getEvent()->getTarget() instanceof Mage_Sales_Model_Quote) {
                throw new InvalidArgumentException('Target is not quote model.');
            }
            $observer->getEvent()
                ->getTarget()
                ->setCustomerGroupId($customerGroupId);
        }
    }

    /**
     * Adds group ID to cookie for FPC
     *
     * @param Varien_Event_Observer $observer
     */
    public function updateCustomerGroupIdCookie(Varien_Event_Observer $observer)
    {
        /* @var $cookie Enterprise_PageCache_Model_Cookie */
        $cookie = Mage::getSingleton('enterprise_pagecache/cookie');

        if ($this->isAdCodeActive()) {
            $customerGroupId = $this->_getCustomerSession()->getAdCodeCustomerGroupId();
            $cookie->setObscure($cookie::COOKIE_CUSTOMER_GROUP, 'customer_group_' . $customerGroupId);
        } else {
            $cookie->delete($cookie::COOKIE_CUSTOMER_GROUP);
        }
    }

    private function _saveAdCode($adCode)
    {
        $session = $this->_getCustomerSession();

        if ($adCode instanceof Tgc_Price_Model_AdCode) {
            $adCode = $adCode->getCode();
        }
        $session->setAdCode($adCode);
        if ($session->isLoggedIn()) {
            $customer = $session->getCustomer();
            $customer->setAdcode($adCode);
            try {
                $customer->save();
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }

    private function _setCustomerGroupId($customerGroupId)
    {
        if (!$this->_validateCustomerGroupId($customerGroupId)) {
            return false;
        }

        $this->_getCustomerSession()
            ->setCustomerGroupId($customerGroupId)
            ->setAdCodeCustomerGroupId($customerGroupId);
        $this->_getCustomerSession()
            ->getCustomer()
            ->setGroupId($customerGroupId);
        if ($this->_getCustomerSession()->isLoggedIn()) {
            try {
                $this->_getCustomerSession()
                    ->getCustomer()
                    ->save();
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
        $this->_getQuote()
            ->setCustomerGroupId($customerGroupId)
            ->save();
        $this->_getQuote()
            ->getShippingAddress()
            ->setCollectShippingRates(true);
    }

    /**
     * Returns customer session
     *
     * @return Mage_Customer_Model_Session
     */
    private function _getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * Returns quote
     *
     * @return Mage_Sales_Model_Quote
     */
    private function _getQuote()
    {
        return Mage::helper('checkout/cart')->getQuote();
    }

    private function _validateCustomerGroupId($groupId)
    {
        $groups = Mage::getModel('customer/group')
            ->getCollection()
            ->addFieldToFilter('customer_group_id', array('eq' => $groupId))
            ->addFieldToFilter('start_date',
                array(
                    array('to' => Mage::getModel('core/date')->gmtDate()),
                    array('start_date', 'null' => ''))
            )
                ->addFieldToFilter('stop_date',
                array(
                    array('gteq' => Mage::getModel('core/date')->gmtDate()),
                    array('stop_date', 'null' => ''))
            )
            ->addFieldtoFilter('website_id', array('in' => array(0, Mage::app()->getWebsite()->getId())));

        return count($groups) > 0 ? true : false;
    }
}
