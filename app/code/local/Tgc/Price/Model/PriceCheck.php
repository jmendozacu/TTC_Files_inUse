<?php
/**
 * Price check model
 *
 * Guaranties that cart will have minimal grand total
 * when coupon is applied with active ad code pricing.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    The Great Courses
 * @package     Adcoderedirect
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Price_Model_PriceCheck
{
    const AD_CODE_ACTIVE     = 1;
    const COUPON_ACTIVE      = 2;
    const ZERO_TOTAL_OCCURED = 4;
    const XML_PATH_SWITCH = 'tgc_price/price_check_enabled';

    /**
     * Returns true if feature is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_SWITCH);
    }

    /**
     * Observer for controller_action_predispatch_checkout_cart_couponPost
     *
     * @param Varien_Event_Observer $observer
     */
    public function checkCouponForMinimalGrandTotal(Varien_Event_Observer $observer)
    {
        if (!$this->isActive()) {
            return;
        }
        $helper = $this->_getHelper();
        $adCodeProcessor = $helper->getAdCodeProcessor();

        if (!$adCodeProcessor->isAdCodeActive()) {
            return;
        }
        try {
            $couponCode = $this->_getCouponCodeFromEvent($observer->getEvent());
            $minPriceCode = $this->_getMinimalPriceCode($couponCode);

            if (!($minPriceCode & self::AD_CODE_ACTIVE)) {
                $adCodeProcessor->resetPrices();
                $this->_getSession()->addSuccess($helper->__(
                    ($minPriceCode & self::ZERO_TOTAL_OCCURED)
                        ? 'Ad code pricing was canceled because cart total is lower without it (since zero total is not allowed).'
                        : 'Ad code pricing was canceled because cart total is lower without it.'
                ));
            }
            if (!($minPriceCode & self::COUPON_ACTIVE)) {
                $this->_resetCouponCodeInEvent($observer->getEvent());
                $message = ($minPriceCode & self::ZERO_TOTAL_OCCURED)
                    ? 'Coupon %s was not applied because cart total is lower without it (since zero total is not allowed).'
                    : 'Coupon %s was not applied because cart total is lower without it.';
                $this->_getSession()->addError($helper->__($message, $helper->escapeHtml($couponCode)));
            }
        } catch (InvalidArgumentException $e) {
            // Coupon was reset by previous events
        }
    }

    /**
     * Observer for controller_action_predispatch_checkout_cart_couponPost
     *
     * @param Varien_Event_Observer $observer
     */
    public function checkCouponForPositiveTotal(Varien_Event_Observer $observer)
    {
        if (!$this->isActive()) {
            return;
        }

        try {
            $couponCode = $this->_getCouponCodeFromEvent($observer->getEvent());
            $total = $this->_getQuoteSubtotalWithDiscount($couponCode, true);

            if (!$this->_isPositive($total)) {
                $helper = $this->_getHelper();
                $this->_resetCouponCodeInEvent($observer->getEvent());
                $this->_getSession()->addError($helper->__(
                    'Unable to apply coupon "%s" because it gives zero total.',
                    $helper->escapeHtml($couponCode)
                ));
            }
        } catch (InvalidArgumentException $e) {
            // Coupon was reset by previous events
        }
    }

    /**
     * Observer for controller_action_predispatch_checkout thats checks cart for positive
     * grand total
     *
     * @param Varien_Event_Observer $observer
     */
    public function checkCartForPositiveTotal(Varien_Event_Observer $observer)
    {
        if (!$this->isActive()) {
            return;
        }

        $quote = $this->_getQuote()
            ->setTotalsCollectedFlag(false)
            ->getShippingAddress()
            ->setCollectShippingRates(true);
        $quote->collectTotals();

        if (!$this->_isPositive($quote->getSubtotalWithDiscount()) && $this->_hasQuoteCoupon()) {
            $helper = $this->_getHelper();
            $this->_getSession()->addError($helper->__(
                'Coupon "%s" has been canceled because it gives zero total.',
                $helper->escapeHtml($quote->getCouponCode())
            ));
            $this->_resetCouponCodeInQuote();
        }

        $quote->setTotalsCollectedFlag(false);
    }

    private function _isPositive($value)
    {
        return $value > .0001;
    }

    private function _getMinimalPriceCode($couponCode)
    {
        $prices = array(
            self::AD_CODE_ACTIVE                        => $this->_getQuoteSubtotalWithDiscount('', true),
            self::COUPON_ACTIVE                         => $this->_getQuoteSubtotalWithDiscount($couponCode, false),
        	self::AD_CODE_ACTIVE + self::COUPON_ACTIVE  => $this->_getQuoteSubtotalWithDiscount($couponCode, true),
        );
        $count = count($prices);
        $prices = array_filter($prices, array($this, '_isPositive'));
        $posCount = count($prices);
        asort($prices);
        reset($prices);
        $code = key($prices);

        if ($count != $posCount) {
            $code += self::ZERO_TOTAL_OCCURED;
        }

        return $code;
    }

    private function _getCouponCodeFromEvent(Varien_Event $event)
    {
        $action = $event->getControllerAction();
        if (!$action) {
            throw new InvalidArgumentException('Event does not have action.');
        }

        $couponCode = $action->getRequest()->getParam('coupon_code');
        if (!strlen($couponCode)) {
            throw new InvalidArgumentException('Request does not have coupon.');
        }

        return $couponCode;
    }

    private function _resetCouponCodeInEvent(Varien_Event $event)
    {
        // Event validation
        $this->_getCouponCodeFromEvent($event);

        // Reset
        $event->getControllerAction()
            ->getRequest()
            ->setParam('coupon_code', '');
    }

    private function _hasQuoteCoupon()
    {
        return strlen($this->_getQuote()->getCouponCode());
    }

    private function _resetCouponCodeInQuote()
    {
        $this->_getQuote()
            ->setCouponCode('')
            ->setTotalsCollectedFlag(false)
            ->save();
    }

    private function _getQuoteSubtotalWithDiscount($couponCode, $adCodeActive)
    {
        return $this->_getQuoteTotal($couponCode, $adCodeActive, 'subtotal_with_discount');
    }

    private function _getQuoteTotal($couponCode, $adCodeActive, $getter)
    {
        $quote = $this->_getQuote();
        $currentCouponCode = $quote->getCouponCode();
        $adCodeProcessor = $this->_getHelper()->getAdCodeProcessor();
        $currentAdCode = $adCodeProcessor->getCurrentAdCode();

        if (!$adCodeActive) {
            $adCodeProcessor->resetPrices();
        }

        $quote->getShippingAddress()->setCollectShippingRates(true);
        $quote->setCouponCode($couponCode)
            ->setTotalsCollectedFlag(false)
            ->collectTotals();

        $total = $quote->getDataUsingMethod($getter);
        $couponInvalid = $couponCode != $quote->getCouponCode();

        $quote->setCouponCode($currentCouponCode)
            ->setTotalsCollectedFlag(false);

        if (!$adCodeActive) {
            $adCodeProcessor->changePrices($currentAdCode);
        }

        if ($couponInvalid) {
            throw new InvalidArgumentException("Coupon $couponCode is invalid.");
        }

        return $total;
    }

    /**
     * @return Mage_Sales_Model_Quote
     */
    private function _getQuote()
    {
        return Mage::getSingleton('checkout/cart')->getQuote();
    }

    /**
     * Returns default helper
     *
     * @return Tgc_Price_Helper_Data
     */
    private function _getHelper()
    {
        return Mage::helper('tgc_price');
    }

    /**
     * Returns customer session
     *
     * @return Mage_Customer_Model_Session
     */
    private function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }
}
