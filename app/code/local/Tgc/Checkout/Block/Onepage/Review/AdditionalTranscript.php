<?php
/**
 * Checkout
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Checkout
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Checkout_Block_Onepage_Review_AdditionalTranscript extends Mage_Core_Block_Template
{
    public function isProductATranscript()
    {
        $isProductATranscript = false;
        $sku = $this->getParentBlock()->getItem()->getProduct()->getSku();
        if($sku) {
            $mediaFormat = Mage::helper('tgc_checkout')->deriveMediaFormatFromSku($sku);
            if($mediaFormat == 'Transcript Book' || $mediaFormat == 'Digital Transcript') {
                $isProductATranscript = true;
            }
        }

        return $isProductATranscript;
    }

    public function getTranscriptMediaFormat()
    {
        $transcriptMediaFormat = false;
        if($this->isProductATranscript()) {
            $sku = $this->getParentBlock()->getItem()->getProduct()->getSku();
            if($sku) {
                $transcriptMediaFormat = Mage::helper('tgc_checkout')->deriveMediaFormatFromSku($sku);
            }
        }

        return $transcriptMediaFormat;
    }

    public function getTranscriptCourseName()
    {
        $parentName = null;

        if($parentItemId = $this->getParentBlock()->getItem()->getTranscriptParentItemId()) {
            $quote = $this->_getCheckoutSession()->getQuote();
            $parentItem = $quote->getItemById($parentItemId);
            if($parentItem instanceof Mage_Sales_Model_Quote_Item) {
                $parentName = $parentItem->getName();
            }
        }

        return $parentName;
    }

    /**
     * Return checkout/session model singleton
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getCheckoutSession()
    {
        return Mage::getSingleton('checkout/session');
    }
}