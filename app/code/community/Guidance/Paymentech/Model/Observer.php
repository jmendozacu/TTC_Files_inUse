<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Guidance_Paymentech_Model_Observer extends Enterprise_Pbridge_Model_Observer
{
    /**
     * Update Payment Profiles functionality switcher
     * @param Varien_Event_Observer $observer
     * @return Enterprise_Pbridge_Model_Observer
     */
    public function updatePaymentProfileStatus(Varien_Event_Observer $observer)
    {
        parent::updatePaymentProfileStatus($observer);

        $website = Mage::app()->getWebsite($observer->getEvent()->getData('website'));
        $enabled = $website->getConfig('payment/paymentech/active')
                        && $website->getConfig('payment/paymentech/payment_profiles_enabled');
        $scope = $observer->getEvent()->getData('website') ? 'websites' : 'default';
        Mage::getConfig()->saveConfig('payment/pbridge/profilestatus', $enabled ? 1 : 0, $scope, $website->getId());
        Mage::dispatchEvent('clean_cache_by_tags', array('tags' => array(Mage_Core_Model_Config::CACHE_TAG)));
    }
}