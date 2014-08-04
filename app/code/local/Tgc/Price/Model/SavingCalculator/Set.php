<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Price_Model_SavingCalculator_Set extends Tgc_Price_Model_SavingCalculator_Product
{
    public function canCalculate(Mage_Catalog_Model_Product $product)
    {
        return $product->getTypeId() == Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE
            && $product->getAttributeSetId() == Mage::helper('tgc_catalog')->getSetAttributeSetId()
            && $product->getSetMembers();
    }

    public function calculate()
    {
        $set = $this->_getProduct();

        return $this->_getConnection()->fetchPairs(
            $this->_getSavingsSelect($set->getId(), explode(',', $set->getSetMembers()))
        );
    }

    private function _getSavingsSelect($setProductId, array $courseProductIds)
    {
        $eav = Mage::getSingleton('eav/config');
        $courseIdAttrId = $eav->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'course_id')->getId();
        $mediaFormatAttrId = $eav->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'media_format')->getId();
        $statusAttrId = $eav->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'status')->getId();
        $storeId = Mage::app()->getStore()->getId();
        $websiteId = Mage::app()->getWebsite()->getId();
        $custGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        $savingExpr = new Zend_Db_Expr('SUM(price.final_price) - set_price.final_price');
        $courseStatusExpr = new Zend_Db_Expr('IF(course_status.value IS NOT NULL, course_status.value, course_status_default.value)');
        $mfStatusExpr = new Zend_Db_Expr('IF(media_format_status.value IS NOT NULL, media_format_status.value, media_format_status_default.value)');

        return $this->_getConnection()->select()
            ->from(
                array('product' => 'catalog_product_entity'),
                array('media_format' => 'o.value', 'saving' => $savingExpr)
            )
            ->join(
                array('course_id' => 'catalog_product_entity_varchar'),
                "course_id.entity_id = product.entity_id AND course_id.attribute_id = $courseIdAttrId",
                array()
            )
            ->join(
                array('sl_course' => 'catalog_product_super_link'),
                'sl_course.product_id = product.entity_id',
                array()
            )
            ->join(
                array('course_status_default' => 'catalog_product_entity_int'),
                "course_status_default.entity_id = sl_course.parent_id AND course_status_default.attribute_id = $statusAttrId AND course_status_default.store_id = 0",
                array()
            )
            ->joinLeft(
                array('course_status' => 'catalog_product_entity_int'),
                "course_status.entity_id = sl_course.parent_id AND course_status.attribute_id = $statusAttrId AND course_status.store_id = $storeId",
                array()
            )
            ->join(
                array('media_format_status_default' => 'catalog_product_entity_int'),
                "media_format_status_default.entity_id = product.entity_id AND media_format_status_default.attribute_id = $statusAttrId AND media_format_status_default.store_id = 0",
                array()
            )
            ->joinLeft(
                array('media_format_status' => 'catalog_product_entity_int'),
                "media_format_status.entity_id = product.entity_id AND media_format_status.attribute_id = $statusAttrId AND media_format_status.store_id = $storeId",
                array()
            )
            ->join(
                array('course_website' => 'catalog_product_website'),
                "course_website.product_id = sl_course.parent_id AND course_website.website_id = $websiteId",
                array()
            )
            ->join(
                array('media_format_website' => 'catalog_product_website'),
                "media_format_website.product_id = product.entity_id AND media_format_website.website_id = $websiteId",
                array()
            )
            ->join(
                array('media_format' => 'catalog_product_entity_int'),
                "media_format.entity_id = product.entity_id AND media_format.attribute_id = $mediaFormatAttrId",
                array()
            )
            ->join(
                array('price' => 'catalog_product_index_price'),
                "price.entity_id = product.entity_id AND price.website_id = $websiteId AND price.customer_group_id = $custGroupId",
                array()
            )
            ->join(
                array('sl' => 'catalog_product_super_link'),
                "sl.parent_id = $setProductId",
                array()
            )
            ->join(
                array('set_media_format' => 'catalog_product_entity_int'),
                "set_media_format.entity_id = sl.product_id AND set_media_format.attribute_id = $mediaFormatAttrId AND set_media_format.value = media_format.value",
                array()
            )
            ->join(
                array('set_price' => 'catalog_product_index_price'),
                "set_price.website_id = $websiteId AND set_price.customer_group_id = $custGroupId AND set_price.entity_id = sl.product_id",
                array()
            )
            ->join(
                array('o' => 'eav_attribute_option_value'),
                'o.option_id = media_format.value AND o.store_id = 0',
                array()
            )
            ->where('course_id.value IN (?)', $courseProductIds)
            ->where("type_id = ?", 'simple')
            ->where($courseStatusExpr)
            ->where($mfStatusExpr)
            ->group('media_format.value')
            ->order("$savingExpr DESC");
    }
}