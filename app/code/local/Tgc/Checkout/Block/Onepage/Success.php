<?php
/**
 * Checkout
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Checkout
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Checkout_Block_Onepage_Success extends Mage_Checkout_Block_Onepage_Success
{

    public function __construct() { $this->getOrder(); }

    public function _getOrder()
    {
        $order = Mage::getModel('sales/order')
            ->load(Mage::getSingleton('checkout/session')->getLastOrderId());

        Mage::register('order', $order, true);

        return $order;
    }

    public function getQuote()
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
