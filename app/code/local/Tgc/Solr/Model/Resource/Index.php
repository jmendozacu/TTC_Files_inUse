<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Solr
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Solr_Model_Resource_Index extends Enterprise_Search_Model_Resource_Index
{
    /**
     * Retrieve onSale flags data for product
     *
     * @param   array $productIds
     * @param   int $storeId
     * @param   int $customerGroup
     * @param   Varien_Db_Adapter_Interface $adapter
     *
     * @return  array
     */
    public function getOnSaleIndexData($productIds, $storeId, $customerGroup = null, $adapter = null)
    {
        if (!$adapter) {
            $adapter = $this->_getWriteAdapter();
        }

        $websiteId = Mage::app()->getStore($storeId)->getWebsiteId();

        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->setStore(Mage::app()->getStore($storeId));
        $collection->getSelect()->joinInner(
            array('link' => $this->getTable('catalog/product_super_link')),
            'link.product_id = e.entity_id'.
                ($productIds ? $adapter->quoteInto(' AND link.parent_id IN (?)', $productIds) : ''),
            array('link_parent_id' => 'link.parent_id')
        );

        $collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left');

        $collection->getSelect()->where('e.sku NOT LIKE ?', 'PT%')
            ->where('e.sku NOT LIKE ?', 'DT%');

        $result = array();
        $select = $collection->getSelect();
        $stmt = $adapter->query($select);
        $simpleProductsForParentProducts = array();
        while ($row = $stmt->fetch()) {
            $simpleProductsForParentProducts[$row['link_parent_id']][$row['entity_id']] = $row['price'];
        }

        foreach ($simpleProductsForParentProducts as $parentId => &$simpleProductInfo) {
            $simpleProductIds = array_keys($simpleProductInfo);
            if ($simpleProductIds) {
                $select = $adapter->select()
                    ->from(
                        $this->getTable('catalog/product_index_price'),
                        array('customer_group_id', 'entity_id', 'final_price')
                    )
                    ->where('entity_id IN (?)', $simpleProductIds)
                    ->where('website_id = ?', $websiteId);
                if (!is_null($customerGroup)) {
                    $select->where('customer_group_id = ?', $customerGroup);
                }
                $stmt = $adapter->query($select);
                while ($row = $stmt->fetch()) {
                    if (!isset($result[$parentId][$row['customer_group_id']]) &&
                        isset($simpleProductInfo[$row['entity_id']]) &&
                        $row['final_price'] < $simpleProductInfo[$row['entity_id']]
                    ) {
                        $result[$parentId][$row['customer_group_id']] = 1;
                    }
                }
            }
        }

        return $result;
    }

    public function getCourseDataForSets($productIds, $storeId)
    {
        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->setStore(Mage::app()->getStore($storeId))
            ->joinAttribute('set_members', 'catalog_product/set_members', 'entity_id', null, 'left');

        if ($productIds) {
            $collection->addAttributeToFilter('entity_id', array('in' => $productIds));
        }

        $setMembers = array();
        $courseIds = array();

        $select = $collection->getSelect();
        $adapter = $this->_getWriteAdapter();
        $stmt = $adapter->query($select);
        while ($row = $stmt->fetch()) {
            if ($row['attribute_set_id'] == Mage::helper('tgc_catalog')->getSetAttributeSetId()) {
                $setMembers[$row['entity_id']] = array_map('trim', explode(',', $row['set_members']));
                $courseIds = array_merge($courseIds, $setMembers[$row['entity_id']]);
            }
        }

        if ($courseIds) {
            $courseIds = array_unique($courseIds);

            $coursesCollection = Mage::getResourceModel('tgc_dl/course_collection')
                ->setStore(Mage::app()->getStore($storeId))
                ->joinAttribute('short_description', 'catalog_product/short_description', 'entity_id', null, 'left')
                ->joinAttribute('name', 'catalog_product/name', 'entity_id', null, 'left')
                ->addFieldToFilter('course_id', array('in' => $courseIds));

            $select = $coursesCollection->getSelect();
            $courseData = array();
            $stmt = $adapter->query($select);
            while ($row = $stmt->fetch()) {
                $courseData[$row['course_id']] = array(
                    'short_description' => $row['name']. ' '. $row['short_description']
                );
            }

            $setData = array();
            foreach ($setMembers as $setId => $courses) {
                foreach ($courses as $courseId) {
                    if (isset($courseData[$courseId])) {
                        $setData[$setId][$courseId] = $courseData[$courseId];
                    }
                }
            }
            return $setData;
        }

        return array();
    }
}