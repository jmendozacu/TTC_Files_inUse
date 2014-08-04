<?php
/**
 * Omniture
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Omniture
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Omniture_Block_Checkout_Success extends Mage_Core_Block_Template
{
    private $_order;

    private function _getOrder()
    {
        if (isset($this->_order)) {
            return $this->_order;
        }

        $session  = Mage::getSingleton('checkout/session');
        $orderId  = $session->getLastOrderId();
        $order    = Mage::getModel('sales/order')->load($orderId);

        if (!$order->getId()) {
            $order = false;
        }

        $this->_order = $order;

        return $this->_order;
    }

    public function isOrderAvailable()
    {
        $order = (bool)$this->_getOrder();

        return $order;
    }

    public function getProductPurchasedIds()
    {
        $order = $this->_getOrder();
        $items = $order->getAllVisibleItems();
        $productIds = array();
        foreach ($items as $item) {
            $productIds[] = $item->getProductId();
        }

        return join(',', $productIds);
    }

    public function getOrderTotal()
    {
        $order = $this->_getOrder();

        return $order->getGrandTotal();
    }

    public function getOrderId()
    {
        $order = $this->_getOrder();

        return $order->getIncrementId();
    }
}
