<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2014-03-28T14:38:13+01:00
 * File:          app/code/local/Xtento/ProductExport/Model/Export/Data/Product/Categories.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Model_Export_Data_Product_Categories extends Xtento_ProductExport_Model_Export_Data_Abstract
{
    /**
     * Category cache
     */
    protected static $_categoryCache = array();

    public function getConfiguration()
    {
        // Reset cache
        self::$_categoryCache = array();

        return array(
            'name' => 'Product category information',
            'category' => 'Product',
            'description' => 'Export product categories for the given product.',
            'enabled' => true,
            'apply_to' => array(Xtento_ProductExport_Model_Export::ENTITY_PRODUCT),
        );
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = array();
        $this->_writeArray = & $returnArray['cats'];

        if (!$this->fieldLoadingRequired('cats')) {
            return $returnArray;
        }

        if (!isset(self::$_categoryCache[$this->getStoreId()])) {
            self::$_categoryCache[$this->getStoreId()] = array();
        }

        // Fetch fields to export
        $product = $collectionItem->getProduct();

        $categoryIds = $product->getCategoryIds();

        foreach ($categoryIds as $categoryId) {
            $this->_writeArray = & $returnArray['cats'][];

            if (!array_key_exists($categoryId, self::$_categoryCache[$this->getStoreId()])
                || (array_key_exists($categoryId, self::$_categoryCache[$this->getStoreId()]) && !is_array(self::$_categoryCache[$this->getStoreId()][$categoryId]))
            ) {
                if (array_key_exists($categoryId, self::$_categoryCache[$this->getStoreId()]) && !is_array(self::$_categoryCache[$this->getStoreId()][$categoryId])) {
                    $category = self::$_categoryCache[$this->getStoreId()][$categoryId];
                } else {
                    if ($this->getStoreId()) {
                        $category = Mage::getModel('catalog/category')->setStoreId($this->getStoreId())->load($categoryId);
                    } else {
                        $category = Mage::getModel('catalog/category')->load($categoryId);
                    }
                }

                foreach ($category->getData() as $key => $value) {
                    $attribute = $category->getResource()->getAttribute($key);
                    $attrText = '';
                    if ($attribute) {
                        $attrText = $category->getAttributeText($key);
                    }
                    if (!empty($attrText)) {
                        $this->writeValue($key, $attrText);
                    } else {
                        $this->writeValue($key, $value);
                    }
                }

                // Build category path
                $pathIds = $category->getPathIds();
                $pathAsName = "";
                foreach ($pathIds as $pathCatId) {
                    $catName = "";
                    if (array_key_exists($pathCatId, self::$_categoryCache[$this->getStoreId()])
                        && isset(self::$_categoryCache[$this->getStoreId()][$pathCatId]['name'])
                    ) {
                        $catName = self::$_categoryCache[$this->getStoreId()][$pathCatId]['name'];
                    } else {
                        $category = Mage::getModel('catalog/category')->load($pathCatId);
                        if ($this->getStoreId()) {
                            $category->setStoreId($this->getStoreId());
                        }
                        $catName = $category->getName();
                        self::$_categoryCache[$this->getStoreId()][$pathCatId] = $category;
                    }
                    if (!empty($catName)) {
                        if (empty($pathAsName)) {
                            $pathAsName = $catName;
                        } else {
                            $pathAsName .= " > " . $catName;
                        }
                    }
                }
                $this->writeValue('path_name', $pathAsName);

                // Get product incl. category path URL
                $productUrl = $product->getUrlPath($category);
                if ($this->getProfile()->getExportUrlRemoveStore()) {
                    if (preg_match("/&/", $productUrl)) {
                        $productUrl = preg_replace("/___store=(.*?)&/", "&", $productUrl);
                    } else {
                        $productUrl = preg_replace("/\?___store=(.*)/", "", $productUrl);
                    }
                }
                $productUrl = Mage::getUrl($productUrl, array('_store' => $this->getStoreId()));
                $this->writeValue('product_url', $productUrl);

                // Cache category
                self::$_categoryCache[$this->getStoreId()][$categoryId] = $this->_writeArray;
            } else {
                // Copy from cache
                $this->_writeArray = self::$_categoryCache[$this->getStoreId()][$categoryId];
            }
        }

        $this->_writeArray = & $returnArray;
        // Done
        return $returnArray;
    }
}