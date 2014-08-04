<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Checkout_Model_Observer
{
    /** @var bool */
    protected $_evaluated = false;

    public function changeIsProspectToFalse(Varien_Event_Observer $observer)
    {
        $customerId = $observer->getQuote()->getCustomerId();
        if($customerId) {
            $customer = Mage::getModel('customer/customer')->load($customerId);
            if($customer->getId()) {
                $customer->setIsProspect(false)->save();
            } else {
                Mage::log('failed to set is_prospect to false, because there is no customer in the database corresponding to the id in the quote.');
            }
        }
    }

    public function passDataFromCheckoutformToCustomerform(Varien_Event_Observer $observer)
    {
        $request = Mage::app()->getFrontController()->getAction()->getRequest();
        $checkoutRegFormData = $request->getPost('registercheckout');
        if($request->getParam('referringpage') == 'checkout' && $checkoutRegFormData) {
            $formRegisterBlock = Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('customer_form_register');
            $formInput = new Varien_Object($checkoutRegFormData);
            $formRegisterBlock->setFormData($formInput);
        }
    }

    public function migrateCartData(Varien_Event_Observer $observer)
    {
        if ($this->_evaluated) {
            return;
        }
        $cookie = Mage::getModel('core/cookie')->get(Tgc_Checkout_Model_Cart_Migrate::CART_MIGRATE_COOKIE_NAME);
        if (!$cookie) {
            // Get "store" cookie value with web_user_id to find out if there are items to migrate
            $store_value = Mage::getModel('core/cookie')->get('store');
            if ($store_value) {
                $store_values = array();
                // parse for web_user_id
                parse_str($store_value, $store_values);
                // Check cart migration table for matching items
                $migrate = Mage::getModel('tgc_checkout/cart_migrate');
                $migrate->doMigration($observer->getQuote(), $store_values);

            }
            // Set cookie value
            $value = 1;
            $expire = 86400 * 365; // expire in 1 year
            Mage::getModel('core/cookie')->set(Tgc_Checkout_Model_Cart_Migrate::CART_MIGRATE_COOKIE_NAME, $value, $expire);
        }
        $this->_evaluated = true;
        return;
    }

    /**
     * On customer login we unset the cart_merge cookie to give it a chance to migrate based on the updated web user id
     * from customer history
     *
     * @param Varien_Event_Observer $observer
     */
    public function handleCustomerLogin(Varien_Event_Observer $observer)
    {
        Mage::getModel('core/cookie')->delete(Tgc_Checkout_Model_Cart_Migrate::CART_MIGRATE_COOKIE_NAME);
    }

    public function handleLoginCartMerge(Varien_Event_Observer $observer)
    {
        $request = Mage::app()->getFrontController()->getRequest();
        $referrer = $request->getServer('HTTP_REFERER');
        $oldQuote = Mage::getModel('sales/quote')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->loadByCustomer(Mage::getSingleton('customer/session')->getCustomerId());

        if (strpos($referrer, 'checkout/onepage') === false) {
            //not checkout page login, duplicate items should have new quote quantities
            $newQuote = $observer->getEvent()->getCheckoutSession()->getQuote();
            if ($oldQuote->getId() == $newQuote->getId()) {
                return;
            }
            $newItems = $newQuote->getAllItems();
            $newIds = array();
            $oldItems = $oldQuote->getAllItems();
            $oldIds = array();
            foreach ($newItems as $newItem) {
                $newIds[] = $newItem->getProductId();
            }
            foreach ($oldItems as $oldItem) {
                $oldIds[] = $oldItem->getProductId();
            }
            $intersect = array_intersect($oldIds, $newIds);
            if (empty($intersect)) {
                return;
            }
            foreach ($oldQuote->getAllItems() as $item) {
                if (in_array($item->getProductId(), $intersect)) {
                    $oldQuote->removeItem($item->getId());
                }
            }
            $oldQuote->save();
            return;
        }
        //login from checkout, delete old quote items
        foreach($oldQuote->getAllItems() as $item) {
            $oldQuote->removeItem($item->getId());
        }
        $oldQuote->save();
    }
}
