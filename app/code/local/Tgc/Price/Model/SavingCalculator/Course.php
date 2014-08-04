<?php
/**
 * Saving calculation strategy for course
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Price_Model_SavingCalculator_Course extends Tgc_Price_Model_SavingCalculator_Product
{
    public function canCalculate(Mage_Catalog_Model_Product $product)
    {
        return $product->getTypeId() == Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE
            && $product->getAttributeSetId() == Mage::helper('tgc_catalog')->getCourseAttributeSetId();
    }

    public function calculate()
    {
        $set = $this->_getProduct();

        return $this->_getConnection()->fetchPairs(
                $this->_getSavingsSelect($set->getId(), explode(',', $set->getSetMembers()))
        );
    }

    private function _getSavingsSelect($productId)
    {
        $eav = Mage::getSingleton('eav/config');
        $statusAttrId = $eav->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'status')->getId();
        $priceAttrId = $eav->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'price')->getId();
        $storeId = Mage::app()->getStore()->getId();
        $websiteId = Mage::app()->getWebsite()->getId();
        $custGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        $savingExpr = new Zend_Db_Expr('list_price.value - price.final_price');
        $statusExpr = new Zend_Db_Expr('IF(status.value IS NOT NULL, status.value, status_default.value)');

        return $this->_getConnection()->select()
            ->from(
                array('sl' => 'catalog_product_super_link'),
                array('sl.product_id', $savingExpr)
            )
            ->join(
                array('status_default' => 'catalog_product_entity_int'),
                "status_default.entity_id = sl.product_id AND status_default.attribute_id = $statusAttrId AND status_default.store_id = 0",
                array()
            )
            ->joinLeft(
                array('status' => 'catalog_product_entity_int'),
                "status.entity_id = sl.product_id AND status.attribute_id = $statusAttrId AND status.store_id = $storeId",
                array()
            )
            ->join(
                array('website' => 'catalog_product_website'),
                "website.product_id = sl.product_id AND website.website_id = $websiteId",
                array()
            )
            ->join(
                array('price' => 'catalog_product_index_price'),
                "price.entity_id = sl.product_id AND price.website_id = $websiteId AND price.customer_group_id = $custGroupId",
                array()
            )
            ->join(
                array('list_price' => 'catalog_product_entity_decimal'),
                "list_price.attribute_id = $priceAttrId AND list_price.entity_id = sl.product_id AND list_price.store_id = $storeId",
                array()
            )
            ->where($statusExpr)
            ->where('sl.parent_id = ?', $productId)
            ->order("($savingExpr) DESC");
    }
}