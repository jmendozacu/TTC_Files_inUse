<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2013-11-26T16:51:05+01:00
 * File:          app/code/local/Xtento/ProductExport/Model/Export/Data/Product/Children.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Model_Export_Data_Product_Children extends Xtento_ProductExport_Model_Export_Data_Product_General
{
    public function getConfiguration()
    {
        return array(
            'name' => 'Child product information',
            'category' => 'Product',
            'description' => 'Export child products of configurable products',
            'enabled' => true,
            'apply_to' => array(Xtento_ProductExport_Model_Export::ENTITY_PRODUCT),
        );
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = array();

        // Fetch product - should be a "parent" item
        $product = $collectionItem->getProduct();
        if ($product->getTypeId() !== Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
            return $returnArray;
        }

        // Find & export child item
        if ($this->fieldLoadingRequired('child_products')) {
            $returnArray['child_products'] = array();
            $originalWriteArray = & $this->_writeArray;
            $this->_writeArray = & $returnArray['child_products']; // Write on child_item level

            $childProducts = $product->getTypeInstance()->getUsedProductCollection($product);
            $childProducts->addAttributeToSelect('*');
            $childProducts->joinField('qty', 'cataloginventory/stock_item', 'qty', 'product_id=entity_id', '{{table}}.stock_id=1', 'left');
            $childProducts->addTaxPercents();
            if ($this->getProfile()->getStoreIds()) {
                $childProducts->getSelect()->joinLeft(Mage::getSingleton('core/resource')->getTableName('catalog_product_index_price') . ' AS price_index', 'price_index.entity_id=e.entity_id AND customer_group_id=0 AND  price_index.website_id=' . Mage::getModel('core/store')->load($this->getProfile()->getStoreIds())->getWebsiteId(), array('min_price' => 'min_price', 'max_price' => 'max_price', 'tier_price' => 'tier_price', 'final_price' => 'final_price'));
                $childProducts->addStoreFilter($this->getProfile()->getStoreIds());
                $childProducts->addAttributeToSelect("tax_class_id");
            }
            foreach ($childProducts as $childProduct) {
                $this->_writeArray = & $returnArray['child_products'][];
                if ($this->getStoreId()) {
                    $childProduct->setStoreId($this->getStoreId());
                }
                $this->_exportProductData($childProduct, $returnArray);
                if ($this->fieldLoadingRequired('child_products/cats')) {
                    // Export categories for child product
                    $fakedCollectionItem = new Varien_Object();
                    $fakedCollectionItem->setProduct($childProduct);
                    $exportClass = Mage::getSingleton('xtento_productexport/export_data_product_categories');
                    $exportClass->setProfile($this->getProfile());
                    $exportClass->setShowEmptyFields($this->getShowEmptyFields());
                    $returnData = $exportClass->getExportData(Xtento_ProductExport_Model_Export::ENTITY_PRODUCT, $fakedCollectionItem);
                    if (is_array($returnData) && !empty($returnData)) {
                        $this->_writeArray = array_merge_recursive($this->_writeArray, $returnData);
                    }
                }
            }
            $this->_writeArray = & $originalWriteArray;
        }
        $this->_writeArray = & $returnArray; // Write on product level

        // Done
        return $returnArray;
    }
}