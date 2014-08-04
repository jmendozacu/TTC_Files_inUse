<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Model_Observer
{
    /**
     * Local storage for quote items discount percents
     *
     * @var array
     */
    protected $_quoteItemsLatestDiscountPercent = array();

    public function invalidateBlockCache()
    {
        try {
            Mage::app()->getCacheInstance()->invalidateType(Mage_Core_Block_Abstract::CACHE_GROUP);
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    /**
     * Set "from_buffet_landing" flag to quote item
     * observes sales_quote_add_item
     *
     * @param Varien_Event_Observer $observer
     * @return \Tgc_Datamart_Model_Observer
     */
    public function flagBuffetQuoteItem(Varien_Event_Observer $observer)
    {
        $isFromBuffetLanding = Mage::app()->getRequest()->getParam('from_buffet_landing');
        if (!$isFromBuffetLanding) {
            return $this;
        }

        $quoteItem = $observer->getEvent()->getQuoteItem();
        if (!$quoteItem) {
            return $this;
        }

        $quoteItem->setFromBuffetLanding(true);

        return $this;
    }

    /**
     * Force "from_buffet_landing" flagged quote item qty to 1
     * observes sales_quote_item_qty_set_after
     *
     * @param Varien_Event_Observer $observer
     * @return \Tgc_Datamart_Model_Observer
     */
    public function forceBuffetQuoteItemQty(Varien_Event_Observer $observer)
    {
        $quoteItem = $observer->getEvent()->getItem();
        if ($quoteItem->getFromBuffetLanding()) {
            $quoteItem->setData('qty', 1);
        }
        return $this;
    }

    /**
     * Forbid buffet product duplicates in quote
     * observes sales_quote_product_add_after
     *
     * @param Varien_Event_Observer $observer
     * @return \Tgc_Datamart_Model_Observer
     */
    public function forbidBuffetProductDuplicatesInQuote(Varien_Event_Observer $observer)
    {
        $addedQuoteItems = $observer->getEvent()->getItems();
        if (!$addedQuoteItems || !is_array($addedQuoteItems)) {
            return $this;
        }

        foreach ($addedQuoteItems as $addedQuoteItem) {
            if ($addedQuoteItem->getParentItem() || !$addedQuoteItem->getFromBuffetLanding()) {
                continue;
            }
            foreach ($addedQuoteItem->getQuote()->getAllVisibleItems() as $quoteItem) {
                if ($quoteItem->getId() != $addedQuoteItem->getId()
                    && $quoteItem->getFromBuffetLanding()
                    && $quoteItem->getProduct()->getId() == $addedQuoteItem->getProduct()->getId()) {
                    $addedQuoteItem->getQuote()->deleteItem($addedQuoteItem);
                    Mage::throwException(
                        Mage::helper('tgc_datamart')->__(
                            'You can buy only one format of the %s',
                            Mage::helper('core')->escapeHtml($quoteItem->getProduct()->getName())
                        )
                    );
                }
            }
        }

        return $this;
    }

    /**
     * Forbid sales rule discount on buffet quote item
     *
     * @param Varien_Event_Observer $observer
     * @return \Tgc_Datamart_Model_Observer
     */
    public function forbidSalesRuleDiscountOnBuffetQuoteItem(Varien_Event_Observer $observer)
    {
        $rule = $observer->getEvent()->getRule();
        $item = $observer->getEvent()->getItem();
        if ($rule && $rule->getCouponType() != Mage_SalesRule_Model_Rule::COUPON_TYPE_NO_COUPON
            && $item->getFromBuffetLanding()) {
            $result = $observer->getEvent()->getResult();
            $result->setDiscountAmount(0)
                ->setBaseDiscountAmount(0);
            if (isset($this->_quoteItemsLatestDiscountPercent[$item->getId()])) {
                $item->setDiscountPercent($this->_quoteItemsLatestDiscountPercent[$item->getId()]);
            } else {
                $item->setDiscountPercent(0);
            }
        }

        $this->_quoteItemsLatestDiscountPercent[$item->getId()] = $item->getDiscountPercent();

        return $this;
    }
}
