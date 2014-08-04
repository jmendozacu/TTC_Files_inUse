<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Wishlist
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Wishlist_Block_Customer_Wishlist_Item_Column_Cart extends Mage_Wishlist_Block_Customer_Wishlist_Item_Column_Cart
{
    /**
     * Determines if the wishlist item is a digital product or not.
     * @return bool
     */
    public function isWishlistItemDigital()
    {
        $isDigital = false;
        $tgcCheckoutHelper = Mage::helper('tgc_checkout');
        $tgcCheckoutHelper->setCurrentItem($this->getItem());
        if($itemMediaFormatTextValue = $this->getWishlistItemMediaTextValue()) {
            $isDigital = $tgcCheckoutHelper->determineProductsTranscriptType($itemMediaFormatTextValue) == Tgc_Checkout_Helper_Data::TRANSCRIPT_TYPE_DIGITAL? true : false;
        }

        return $isDigital;
    }

    /**
     * Wishlist option value.
     * @param Mage_Wishlist_Model_Item $item
     * @return bool|mixed
     */
    public function getWishlistItemMediaTextValue()
    {
        $mediaFormatTextValue = false;
        if($mediaFormatIdValue = $this->getWishlistItemMediaAttributeValue()) {
            $mediaFormatTextValue = $this->getWishlistItemMediaAttributeValueText($mediaFormatIdValue);
        }

        return $mediaFormatTextValue;
    }

    /**
     * Returns the media format of product on the wishlist.
     * @param Mage_Wishlist_Model_Item $item
     * @return bool|mixed
     */
    public function getWishlistItemMediaAttributeValue()
    {
        $mediaFormatValue = false;

        $option = $this->getItem()->getOptionByCode('attributes');
        if($option && $mediaFormatSerialized = $option->getValue()) {
            if($mediaFormatAsArray = unserialize($mediaFormatSerialized)) {
                $mediaFormatValue = array_pop($mediaFormatAsArray);
            }
        }

        return $mediaFormatValue;
    }

    /**
     * @param Mage_Wishlist_Model_Item $item
     * @param $attributeValue
     * @return mixed
     */
    public function getWishlistItemMediaAttributeValueText($attributeValue)
    {
        return $this->getItem()->getProduct()->getResource()
            ->getAttribute('media_format')
            ->getSource()
            ->getOptionText($attributeValue);
    }
}