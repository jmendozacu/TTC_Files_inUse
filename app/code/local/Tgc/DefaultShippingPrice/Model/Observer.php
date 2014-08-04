<?php
/**
 * Sale
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DefaultShippingPrice
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DefaultShippingPrice_Model_Observer
{
    public function setDefaultShippingAddress(Varien_Event_Observer $observer)
    {
        $quote = Mage::getModel('checkout/session')->getQuote();

        $shippingAddress = $quote->getShippingAddress();

        if (!$shippingAddress->getCountryId()) {
            $country = Mage::getStoreConfig('general/country/default');
            $quote->getShippingAddress()
                ->setCountryId($country)
                ->setCollectShippingRates(true);
            $quote->save();
        }
    }
}
