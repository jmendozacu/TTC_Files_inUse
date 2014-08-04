<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_ManaPro
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_ManaPro_Model_Filter_AllTypes_Observer
{
    /**
     * @param Varien_Event_Observer $observer
     */
    public function addOnSaleInfoToCollection($observer)
    {
        /* @var $productCollection Mage_Catalog_Model_Resource_Product_Collection */
        $productCollection = $observer->getEvent()->getCollection();
        $productIds = array();
        foreach ($productCollection as $product) {
            if ($product->getId()) {
                $productIds[] = $product->getId();
            }
        }
        if ($productIds) {
            $currentCustomerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
            $productOnSaleData = Mage::getSingleton('tgc_solr/resource_index')
                ->getOnSaleIndexData(
                    $productIds,
                    $productCollection->getStoreId(),
                    $currentCustomerGroupId,
                    $productCollection->getConnection()
                );
            foreach ($productCollection as $product) {
                if ($product->getId()) {
                    $isOnSale = (!empty($productOnSaleData[$product->getId()]) &&
                        !empty($productOnSaleData[$product->getId()][$currentCustomerGroupId]));
                    $product->setOnSaleFlag($isOnSale);
                }
            }
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function addOnSaleInfoIntoProductAfterLoad($observer)
    {
        /* @var $product Mage_Catalog_Model_Product */
        $product = $observer->getEvent()->getProduct();
        if (!$product->hasData('on_sale_flag') && $product->getId()) {
            $currentCustomerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
            $productOnSaleData = Mage::getSingleton('tgc_solr/resource_index')
                ->getOnSaleIndexData(
                    array($product->getId()),
                    $product->getStoreId(),
                    $currentCustomerGroupId,
                    $product->getResource()->getReadConnection()
                );
            $isOnSale = (
                !empty($productOnSaleData) &&
                !empty($productOnSaleData[$product->getId()]) &&
                !empty($productOnSaleData[$product->getId()][$currentCustomerGroupId])
            );
            $product->setOnSaleFlag($isOnSale);
        }
    }
}
