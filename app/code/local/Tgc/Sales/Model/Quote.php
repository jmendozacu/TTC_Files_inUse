<?php
/**
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Sales
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Sales_Model_Quote extends Mage_Sales_Model_Quote
{
    /**
     * Check quote for virtual product and NonDigital item
     *
     * @return bool
     */
    public function isVirtual()
    {
        $isVirtual = true;
        $countItems = 0;

        $mediaAttributeIds = Mage::helper('tgc_catalog')->getDigitalMediaAttributeId();

        foreach ($this->getItemsCollection() as $_item) {
            /* @var $_item Mage_Sales_Model_Quote_Item */
            if ($_item->isDeleted() || $_item->getParentItemId()) {
                continue;
            }
            $countItems++;

            $mediaId = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('media_format')
                ->addAttributeToFilter('sku', $_item->getProduct()->getSku())
                ->getFirstItem()
                ->getMediaFormat();

            if (!$_item->getProduct()->getIsVirtual() && !in_array($mediaId, $mediaAttributeIds)) {
                $isVirtual = false;
                break;
            }

        }
        return $countItems == 0 ? false : $isVirtual;
    }

    /**
     * get total baseprice for digital items
     * @return float|int
     */
    public function getSubtotalForDigitalItem()
    {
        $totalDigitalValue = 0;
        $mediaAttributeIds = Mage::helper('tgc_catalog')->getDigitalMediaAttributeId();

        foreach ($this->getItemsCollection() as $_item) {
            /* @var $_item Mage_Sales_Model_Quote_Item */
            if ($_item->isDeleted() || $_item->getParentItemId()) {
                continue;
            }

            $mediaId = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('media_format')
                ->addAttributeToFilter('sku', $_item->getProduct()->getSku())
                ->getFirstItem()
                ->getMediaFormat();

            if (in_array($mediaId, $mediaAttributeIds)) {
                $totalDigitalValue += $_item->getBasePrice();
            }

        }
        return $totalDigitalValue;
    }

    /**
     * get basetotal for digital items with discount
     * @return float|int
     */
    public function getSubtotalForDigitalItemWithDiscount()
    {
        $totalDigitalValue = 0;
        $mediaAttributeIds = Mage::helper('tgc_catalog')->getDigitalMediaAttributeId();

        foreach ($this->getItemsCollection() as $_item) {
            /* @var $_item Mage_Sales_Model_Quote_Item */
            if ($_item->isDeleted() || $_item->getParentItemId()) {
                continue;
            }

            $mediaId = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('media_format')
                ->addAttributeToFilter('sku', $_item->getProduct()->getSku())
                ->getFirstItem()
                ->getMediaFormat();

            if (in_array($mediaId, $mediaAttributeIds)) {
                $price = $_item->getBasePrice() + $_item->getBaseDiscountAmount();
                $totalDigitalValue += $price;
            }

        }
        return $totalDigitalValue;
    }

    /**
     * get total baseprice including tax
     * @return float|int
     */
    public function getSubtotalForDigitalItemInclTax()
    {
        $totalDigitalValue = 0;
        $mediaAttributeIds = Mage::helper('tgc_catalog')->getDigitalMediaAttributeId();

        foreach ($this->getItemsCollection() as $_item) {
            /* @var $_item Mage_Sales_Model_Quote_Item */
            if ($_item->isDeleted() || $_item->getParentItemId()) {
                continue;
            }

            $mediaId = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('media_format')
                ->addAttributeToFilter('sku', $_item->getProduct()->getSku())
                ->getFirstItem()
                ->getMediaFormat();

            if (in_array($mediaId, $mediaAttributeIds)) {
                $price = $_item->getBasePriceInclTax();
                $totalDigitalValue += $price;
            }

        }
        return $totalDigitalValue;
    }

}
