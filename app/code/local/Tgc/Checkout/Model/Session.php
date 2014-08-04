<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Checkout_Model_Session extends Mage_Checkout_Model_Session
{
    /** @var array */
    protected $_mediaFormatArray = array("Audio Download", "Video Download", "Soundtrack Download");
    
    /**
     * Load data for customer quote and merge with current quote
     *
     * @return Mage_Checkout_Model_Session
     */
    public function loadCustomerQuote()
    {
        if (!Mage::getSingleton('customer/session')->getCustomerId()) {
            return $this;
        }

        Mage::dispatchEvent('load_customer_quote_before', array('checkout_session' => $this));

        $customerQuote = Mage::getModel('sales/quote')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->loadByCustomer(Mage::getSingleton('customer/session')->getCustomerId());

        if ($customerQuote->getId() && $this->getQuoteId() != $customerQuote->getId()) {
            if ($this->getQuoteId()) {
                $customerQuote->merge($this->getQuote())
                    ->collectTotals()
                    ->save();
            }

            $this->setQuoteId($customerQuote->getId());

            if ($this->_quote) {
                $this->_quote->delete();
            }
            $this->_quote = $customerQuote;
        } else {
            $this->getQuote()->getBillingAddress();
            $this->getQuote()->getShippingAddress();
            $this->getQuote()->setCustomer(Mage::getSingleton('customer/session')->getCustomer())
                ->setTotalsCollectedFlag(false)
                ->collectTotals()
                ->save();
        }
        
        $quote = $this->getQuote();
        
        $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', 'media_format');
        $attributeSource = $attribute->getSource();
        
        $mediaAttributeIds = array();
        foreach ($this->_mediaFormatArray as $type) {
            $mediaAttributeIds[] = $attributeSource->getOptionId($type);
        }
        
        $allItems = $quote->getAllItems();
        if (count($allItems) > 0) {
            /* @item Mage_Sales_Model_Quote_Item */
            foreach ($allItems as $item) {
                $product = $item->getProduct();
                if (in_array($product->getMediaFormat(), $mediaAttributeIds)) {
                    if ($item->getParentItemId()) {
                        if ($item->getParentItem()->getQty() > 1) {
                            $item->getParentItem()->setQty(1);
                            $item->getParentItem()->save();
                            $quote->setTotalsCollectedFlag(false);
                            $quote->collectTotals();
                            $quote->save();
                        }
                    } else {
                        if ($item->getQty() > 1) {
                            $item->setQty(1);
                            $item->save();
                            $quote->setTotalsCollectedFlag(false);
                            $quote->collectTotals();
                            $quote->save();
                        }
                    }
                }
            }
        }
        
        return $this;
    }
}
