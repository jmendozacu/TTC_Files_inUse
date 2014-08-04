<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2013-09-20T11:27:44+02:00
 * File:          app/code/local/Xtento/ProductExport/Model/Export/Data/Product/Parent.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Model_Export_Data_Product_Parent extends Xtento_ProductExport_Model_Export_Data_Product_General
{
    /**
     * Parent product cache
     */
    protected static $_parentProductCache = array();

    public function getConfiguration()
    {
        // Reset cache
        self::$_parentProductCache = array();

        return array(
            'name' => 'Parent item information',
            'category' => 'Product',
            'description' => 'Export parent item',
            'enabled' => true,
            'apply_to' => array(Xtento_ProductExport_Model_Export::ENTITY_PRODUCT),
        );
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = array();

        // Fetch product - should be a child
        $product = $collectionItem->getProduct();

        $parentId = -1;
        // Check if it's a child product, and if yes, find & export parent id
        if ($this->fieldLoadingRequired('parent_id')) {
            $this->_writeArray = & $returnArray; // Write on product level
            $parentId = $this->_getFirstParentProductId($product);
            $this->writeValue('parent_id', $parentId);
        }

        if (!isset(self::$_parentProductCache[$this->getStoreId()])) {
            self::$_parentProductCache[$this->getStoreId()] = array();
        }

        // Find & export parent item
        if ($this->fieldLoadingRequired('parent_item')) {
            $returnArray['parent_item'] = array();
            $this->_writeArray = & $returnArray['parent_item']; // Write on parent_item level
            if ($parentId == -1) {
                $parentId = $this->_getFirstParentProductId($product);
            }
            if ($parentId) {
                if (!array_key_exists($parentId, self::$_parentProductCache[$this->getStoreId()])) {
                    $parent = Mage::getModel('catalog/product')->load($parentId);
                    if ($this->getStoreId()) {
                        $parent->setStoreId($this->getStoreId());
                    }
                    if ($parent && $parent->getId()) {
                        // Export product data of parent product
                        $this->_exportProductData($parent, $returnArray);
                        if ($this->fieldLoadingRequired('parent_item/cats')) {
                            // Export categories for parent product
                            $fakedCollectionItem = new Varien_Object();
                            $fakedCollectionItem->setProduct($parent);
                            $exportClass = Mage::getSingleton('xtento_productexport/export_data_product_categories');
                            $exportClass->setProfile($this->getProfile());
                            $exportClass->setShowEmptyFields($this->getShowEmptyFields());
                            $returnData = $exportClass->getExportData(Xtento_ProductExport_Model_Export::ENTITY_PRODUCT, $fakedCollectionItem);
                            if (is_array($returnData) && !empty($returnData)) {
                                $this->_writeArray = array_merge_recursive($this->_writeArray, $returnData);
                            }
                        }
                    }
                    // Cache parent product
                    self::$_parentProductCache[$this->getStoreId()][$parentId] = $this->_writeArray;
                } else {
                    // Copy from cache
                    $this->_writeArray = self::$_parentProductCache[$this->getStoreId()][$parentId];
                }
            }
        }
        $this->_writeArray = & $returnArray; // Write on product level

        // Done
        return $returnArray;
    }

    /**
     * Get parent id of the product
     * @param Mage_Catalog_Model_Product $product
     * @return int
     */
    protected function _getFirstParentProductId($product)
    {
        $parentId = null;
        if ($product->getTypeId() == 'simple') {
            $parentIds = Mage::getModel('catalog/product_type_grouped')->getParentIdsByChild($product->getId());
            if (!$parentIds) {
                $parentIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($product->getId());
            }
            if (isset($parentIds[0])) {
                $parentId = $parentIds[0];
            }
        }

        return $parentId;
    }
}