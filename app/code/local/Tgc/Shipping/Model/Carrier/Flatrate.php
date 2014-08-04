<?php
/**
 * Special shipping flat rate model
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Shipping
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Shipping_Model_Carrier_Flatrate
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{

    protected $_code = 'tgc_flatrate';
    protected $_isFixed = true;

    /**
     * Collect rate for promotional flatrate
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return Mage_Shipping_Model_Rate_Result
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $result = Mage::getModel('shipping/rate_result');
        $shippingPrice = $this->_getShippingPrice();

        if ($shippingPrice !== false) {
            $method = Mage::getModel('shipping/rate_result_method');
            $method->setCarrier('tgc_flatrate');
            $method->setCarrierTitle($this->getConfigData('title'));
            $method->setMethod('tgc_flatrate');
            $method->setMethodTitle($this->getConfigData('name'));
            $method->setPrice($shippingPrice);
            $method->setCost($shippingPrice);
            $result->append($method);
        }

        return $result;
    }

    public function getAllowedMethods()
    {
        return array('tgc_flatrate' => $this->getConfigData('name'));
    }

    private function _getShippingPrice()
    {
        $groupId   = Mage::getSingleton('customer/session')->getCustomerGroupId();
        $websiteId = Mage::app()->getWebsite()->getId();

        $promo = Mage::getModel('tgc_shipping/flatRate')
            ->getCollection()
            ->addFilterByGroup($groupId)
            ->addFilterByWebsite($websiteId)
            ->getFirstItem();

        return $promo->getShippingPrice() ? $promo->getShippingPrice() : false;
    }
}
