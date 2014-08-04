<?php
/**
 * Sales
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Sales
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Sales_Model_Observer
{
    /**
     * After an item has been added to the cart, it sets the sales quote item object variables relating to transcripts.
     * @param Varien_Event_Observer $observer
     */
    public function flagTranscriptProducts(Varien_Event_Observer $observer)
    {
        $request = Mage::app()->getRequest();
        $isTranscriptProduct = $request->getParam('is_transcript_item');
        if($isTranscriptProduct) {
            $quoteItem = $observer->getQuoteItem();
            $quoteItem->setIsTranscriptProduct(true);
            if($transcriptProductParentId = $request->getParam('transcript_parent_item_id')) {
                $quoteItem->setTranscriptParentItemId($transcriptProductParentId);
            }
            if($transcriptType = $request->getParam('transcript_type')) {
                $quoteItem->setTranscriptType($transcriptType);
            }
        }
    }

    /**
     * Deletes all transcript products from cart that do not have a parent product.
     * @param Varien_Event_Observer $observer
     */
    public function eliminateTranscriptItemsWithNoParent(Varien_Event_Observer $observer)
    {
        $connection = Mage::getSingleton('core/resource')->getConnection('read');
        $quote = Mage::helper('checkout/cart')->getQuote();
        $quoteItemIds = array();
        $cartNeedsSaving = false;

        if($quoteId = $quote->getId()) {
            $quoteItemIdsSelect = $connection->select()
                ->from('sales_flat_quote_item', array('item_id'))
                ->where('quote_id = ?', $quoteId);

            $quoteItemIds = $connection->fetchCol($quoteItemIdsSelect);
        }

        foreach($quoteItemIds as $itemId) {
            $_item = $quote->getItemById($itemId);
            if(is_object($_item) && $_item->getId()) {
                if($parentTranscriptItemId = $_item->getTranscriptParentItemId()) {
                    $transcriptsParentItem = $quote->getItemById($parentTranscriptItemId);
                    if(is_null($transcriptsParentItem)) {
                        $_item->delete();
                        $cartNeedsSaving = true;
                    }
                }
            }
        }

        if($cartNeedsSaving) {
            $cart = Mage::helper('checkout/cart')->getCart();
            $cart->save();
        }
    }
}