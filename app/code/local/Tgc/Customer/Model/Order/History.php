<?php
/**
 * Order History Model.
 * Has been overridden to get order history data from TGC's order history web service
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Customer
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Customer_Model_Order_History extends Mage_Core_Model_Abstract
{
    private $_orders;

    public function _construct()
    {
        parent::_construct();
        $customer = Mage::getSingleton('customer/session')->getCustomer();

        $this->_orders = Mage::getModel('tgc_customer/api_orderHistory')->getOrders($customer->getDaxCustomerId());
    }

    public function getOrderDetails($orderId)
    {
        return Mage::getModel('tgc_customer/api_orderHistory')->getDetails($orderId);
    }

    public function getShippingTracking($orderId)
    {
        return Mage::getModel('tgc_customer/api_orderHistory')->getTracking($orderId);
    }

    public function getOrders()
    {
        return $this->_orders;
    }

    public function getOrder($orderId, $daxId)
    {
        return Mage::getModel('tgc_customer/api_orderHistory')->getOrder($orderId, $daxId);
    }
}