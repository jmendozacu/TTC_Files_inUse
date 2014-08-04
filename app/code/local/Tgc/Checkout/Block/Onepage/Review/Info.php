<?php
/**
 * Checkout
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Checkout
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Checkout_Block_Onepage_Review_Info extends Mage_Checkout_Block_Onepage_Review_Info
{
    public function getItems()
    {
        return $this->_getQuote()->getAllVisibleItems();
    }

    public function getTotals()
    {
        return $this->_getQuote()->getTotals();
    }

    private function _getOrder()
    {
        $order = Mage::registry('order');

        return $order;
    }

    private function _getQuote()
    {
        $order = $this->_getOrder();
        $quote = Mage::getModel('sales/quote');

        if ($order && $order->getId()) {
            $quote->load($order->getQuoteId());
        }

        Mage::register('quote', $quote, true);

        return $quote;
    }
}
