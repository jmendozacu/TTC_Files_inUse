<?php
/**
 * Fulltext resource model.
 * We should exclude Giftcards from the collection.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Solr
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Solr_Model_Resource_Fulltext extends Mage_CatalogSearch_Model_Resource_Fulltext
{
    /**
     * Retrieve searchable products per store
     *
     * @param int $storeId
     * @param array $staticFields
     * @param array|int $productIds
     * @param int $lastProductId
     * @param int $limit
     * @return array
     */
    protected function _getSearchableProducts($storeId, array $staticFields, $productIds = null, $lastProductId = 0,
        $limit = 100)
    {
        $websiteId      = Mage::app()->getStore($storeId)->getWebsiteId();
        $writeAdapter   = $this->_getWriteAdapter();

        $select = $writeAdapter->select()
            ->useStraightJoin(true)
            ->from(
                array('e' => $this->getTable('catalog/product')),
                array_merge(array('entity_id', 'type_id', 'attribute_set_id'), $staticFields)
            )
            ->join(
                array('website' => $this->getTable('catalog/product_website')),
                $writeAdapter->quoteInto(
                    'website.product_id=e.entity_id AND website.website_id=?',
                    $websiteId
                ),
                array()
            )
            ->join(
                array('stock_status' => $this->getTable('cataloginventory/stock_status')),
                $writeAdapter->quoteInto(
                    'stock_status.product_id=e.entity_id AND stock_status.website_id=?',
                    $websiteId
                ),
                array('in_stock' => 'stock_status')
            );

        if (!is_null($productIds)) {
            $select->where('e.entity_id IN(?)', $productIds);
        }

        $select->where('type_id <> ?', Enterprise_GiftCard_Model_Catalog_Product_Type_Giftcard::TYPE_GIFTCARD);

        $select->where('e.entity_id>?', $lastProductId)
            ->limit($limit)
            ->order('e.entity_id');

        $result = $writeAdapter->fetchAll($select);

        return $result;
    }

    /**
     * Prepare Fulltext index value for product
     *
     * @param array $indexData
     * @param array $productData
     * @param int $storeId
     * @return string
     */
    protected function _prepareProductIndex($indexData, $productData, $storeId)
    {
        $index = array();

        foreach ($this->_getSearchableAttributes('static') as $attribute) {
            $attributeCode = $attribute->getAttributeCode();

            if (isset($productData[$attributeCode])) {
                $value = $this->_getAttributeValue($attribute->getId(), $productData[$attributeCode], $storeId);
                if ($value) {
                    //For grouped products
                    if (isset($index[$attributeCode])) {
                        if (!is_array($index[$attributeCode])) {
                            $index[$attributeCode] = array($index[$attributeCode]);
                        }
                        $index[$attributeCode][] = $value;
                    }
                    //For other types of products
                    else {
                        $index[$attributeCode] = $value;
                    }
                }
            }
        }

        foreach ($indexData as $entityId => $attributeData) {
            foreach ($attributeData as $attributeId => $attributeValue) {
                $value = $this->_getAttributeValue($attributeId, $attributeValue, $storeId);
                if (!is_null($value) && $value !== false) {
                    $attributeCode = $this->_getSearchableAttribute($attributeId)->getAttributeCode();

                    if (isset($index[$attributeCode])) {
                        $index[$attributeCode][$entityId] = $value;
                    } else {
                        $index[$attributeCode] = array($entityId => $value);
                    }
                }
            }
        }

        if (!$this->_engine->allowAdvancedIndex()) {
            $product = $this->_getProductEmulator()
                ->setId($productData['entity_id'])
                ->setTypeId($productData['type_id'])
                ->setStoreId($storeId);
            $typeInstance = $this->_getProductTypeInstance($productData['type_id']);
            if ($data = $typeInstance->getSearchableData($product)) {
                $index['options'] = $data;
            }
        }

        if (isset($productData['in_stock'])) {
            $index['in_stock'] = $productData['in_stock'];
        }

        if (isset($productData['attribute_set_id'])) {
            $index['attribute_set_id'] = $productData['attribute_set_id'];
        }

        if ($this->_engine) {
            return $this->_engine->prepareEntityIndex($index, $this->_separator);
        }

        return Mage::helper('catalogsearch')->prepareIndexdata($index, $this->_separator);
    }
}