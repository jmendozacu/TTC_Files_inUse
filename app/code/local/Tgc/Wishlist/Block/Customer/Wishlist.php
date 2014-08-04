<?php
 /**
 * Tgc Wishlist
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Wishlist
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Wishlist_Block_Customer_Wishlist extends Mage_Wishlist_Block_Customer_Wishlist
{
    /**
     * Add wishlist conditions to collection
     *
     * @param  Mage_Wishlist_Model_Mysql4_Item_Collection $collection
     * @return Mage_Wishlist_Block_Customer_Wishlist
     */
    protected function _prepareCollection($collection)
    {
        $collection->getSelect()->limit(null);
        $collection->setInStockFilter(true)->setOrder('added_at', 'ASC');
        return $this;
    }
}