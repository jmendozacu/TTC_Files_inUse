<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2014-03-11T21:06:29+01:00
 * File:          app/code/local/Xtento/ProductExport/Model/Export/Data/Product/General.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Model_Export_Data_Product_General extends Xtento_ProductExport_Model_Export_Data_Abstract
{
    /**
     * Cache
     */
    protected static $_attributeSetCache = array();
    protected $_config = array();

    public function getConfiguration()
    {
        // Reset cache
        self::$_attributeSetCache = array();

        return array(
            'name' => 'General product information',
            'category' => 'Product',
            'description' => 'Export extended product information.',
            'enabled' => true,
            'apply_to' => array(Xtento_ProductExport_Model_Export::ENTITY_PRODUCT),
        );
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = array();
        $this->_writeArray = & $returnArray; // Write directly on product level
        // Fetch fields to export
        $product = $collectionItem->getProduct();

        if ($product->getTypeId() && $this->getProfile() && in_array($product->getTypeId(), explode(",", $this->getProfile()->getExportFilterProductType()))) {
            return $returnArray; // Product type should be not exported
        }

        // Timestamps of creation/update
        if ($this->fieldLoadingRequired('created_at_timestamp')) $this->writeValue('created_at_timestamp', Mage::helper('xtento_productexport/date')->convertDateToStoreTimestamp($product->getCreatedAt()));
        if ($this->fieldLoadingRequired('updated_at_timestamp')) $this->writeValue('updated_at_timestamp', Mage::helper('xtento_productexport/date')->convertDateToStoreTimestamp($product->getUpdatedAt()));

        // Which line is this?
        $this->writeValue('line_number', $collectionItem->_currItemNo);
        $this->writeValue('count', $collectionItem->_collectionSize);

        // Export information
        $this->writeValue('export_id', (Mage::registry('product_export_log')) ? Mage::registry('product_export_log')->getId() : 0);

        $this->_exportProductData($product, $returnArray);

        // Done
        return $returnArray;
    }

    /**
     * @param $product Mage_Catalog_Model_Product
     */
    protected function _exportProductData($product, &$returnArray)
    {
        if ($this->getStoreId()) {
            $product->setStoreId($this->getStoreId());
            $this->writeValue('store_id', $this->getStoreId());
        } else {
            $this->writeValue('store_id', 0);
        }

        if (!isset($this->_config['including_tax'])) {
            $this->_config['including_tax'] = Mage::getStoreConfig(Mage_Tax_Model_Config::CONFIG_XML_PATH_PRICE_INCLUDES_TAX, $product->getStore());
        }

        #Zend_Debug::dump($product->getData()); die();
        foreach ($product->getData() as $key => $value) {
            if ($key == 'entity_id') {
                continue;
            }
            if ($key == 'price') {
                $this->writeValue('original_price', $value);
                continue;
            }
            if (!$this->fieldLoadingRequired($key)) {
                continue;
            }
            if ($key == 'cost') {
                $this->writeValue('cost', Mage::getResourceModel('catalog/product')->getAttributeRawValue($product->getId(), 'cost', $this->getStoreId()));
                continue;
            }
            if ($key == 'min_price' || $key == 'max_price') {
                $value = $this->_addTax($product, $value, $key);
            }
            if ($key == 'qty') {
                $value = sprintf('%d', $value);
            }
            if ($key == 'image' || $key == 'small_image' || $key == 'thumbnail') {
                $this->writeValue($key . '_raw', $value);
                $this->writeValue($key, Mage::getBaseUrl('media') . 'catalog/product/' . ltrim($value, '/'));
                continue;
            }
            $attribute = $product->getResource()->getAttribute($key);
            if ($attribute instanceof Mage_Catalog_Model_Resource_Eav_Attribute) {
                $attribute->setStoreId($product->getStoreId());
            }
            #if ($key == 'test') {
            #    var_dump($product->getAttributeText($key), $attribute->getStoreLabel($product->getStore()), $attribute);
            #    die();
            #}
            $attrText = '';
            if ($attribute) {
                if ($attribute->getFrontendInput() === 'weee' || $attribute->getFrontendInput() === 'media_gallery') {
                    // Don't export certain frontend_input values
                    continue;
                }
                try {
                    $attrText = $product->getAttributeText($key);
                } catch (Exception $e) {
                    //echo "Problem with attribute $key: ".$e->getMessage();
                    continue;
                }
            }
            if (!empty($attrText)) {
                if (is_array($attrText)) {
                    // Multiselect:
                    foreach ($attrText as $index => $val) {
                        if (!is_array($index) && !is_array($val)) {
                            $this->writeValue($key . '_value_' . $index, $val);
                        }
                    }
                    $this->writeValue($key, implode(",", $attrText));
                } else {
                    if ($attribute->getFrontendInput() == 'multiselect') {
                        $this->writeValue($key . '_value_0', $attrText);
                    }
                    $this->writeValue($key, $attrText);
                }
            } else {
                $this->writeValue($key, $value);
            }
            if ($key == 'visibility' || $key == 'status' || $key == 'tax_class_id') {
                $this->writeValue($key . '_raw', $value);
            }
        }

        // Extended fields
        if ($this->fieldLoadingRequired('product_url')) {
            $productUrl = $product->getProductUrl(false);
            if ($this->getProfile()->getExportUrlRemoveStore()) {
                if (preg_match("/&/", $productUrl)) {
                    $productUrl = preg_replace("/___store=(.*?)&/", "&", $productUrl);
                } else {
                    $productUrl = preg_replace("/\?___store=(.*)/", "", $productUrl);
                }
            }
            $this->writeValue('product_url', $productUrl);
        }
        if ($this->fieldLoadingRequired('price')) {
            $this->writeValue('price', $this->_getPrice($product));
        }
        if ($this->fieldLoadingRequired('attribute_set_name')) {
            $attributeSetId = $product->getAttributeSetId();
            if (!array_key_exists($attributeSetId, self::$_attributeSetCache)) {
                $attributeSet = Mage::getModel('eav/entity_attribute_set')->load($attributeSetId);
                $attributeSetName = '';
                if ($attributeSet->getId()) {
                    $attributeSetName = $attributeSet->getAttributeSetName();
                    $this->writeValue('attribute_set_name', $attributeSetName);
                }
                self::$_attributeSetCache[$attributeSetId] = $attributeSetName;
            } else {
                $this->writeValue('attribute_set_name', self::$_attributeSetCache[$attributeSetId]);
            }
        }

        // Upsell product IDs / SKUs
        if ($this->fieldLoadingRequired('upsell_product_ids')) {
            $this->writeValue('upsell_product_ids', implode(",", $product->getUpSellProductIds()));
        }
        if ($this->fieldLoadingRequired('upsell_product_skus')) {
            $skus = array();
            foreach ($product->getUpSellProductCollection() as $upsellProduct) {
                $skus[] = $upsellProduct->getSku();
            }
            $this->writeValue('upsell_product_skus', implode(",", $skus));
        }
        // Cross-Sell product IDs / SKUs
        if ($this->fieldLoadingRequired('cross_sell_product_ids')) {
            $this->writeValue('cross_sell_product_ids', implode(",", $product->getCrossSellProductIds()));
        }
        if ($this->fieldLoadingRequired('cross_sell_product_skus')) {
            $skus = array();
            foreach ($product->getCrossSellProductCollection() as $crosssellProduct) {
                $skus[] = $crosssellProduct->getSku();
            }
            $this->writeValue('cross_sell_product_skus', implode(",", $skus));
        }
        // Related product IDs / SKUs
        if ($this->fieldLoadingRequired('related_product_ids')) {
            $this->writeValue('related_product_ids', implode(",", $product->getRelatedProductIds()));
        }
        if ($this->fieldLoadingRequired('related_product_skus')) {
            $skus = array();
            foreach ($product->getRelatedProductCollection() as $relatedProduct) {
                $skus[] = $relatedProduct->getSku();
            }
            $this->writeValue('related_product_skus', implode(",", $skus));
        }
        if ($this->fieldLoadingRequired('website_codes')) {
            $websiteCodes = array();
            foreach ($product->getWebsiteIds() as $websiteId) {
                $websiteCode = Mage::app()->getWebsite($websiteId)->getCode();
                $websiteCodes[$websiteCode] = $websiteCode;
            }
            $this->writeValue('website_codes', join(',', $websiteCodes));
        }

        if ($this->fieldLoadingRequired('images')) {
            $returnArray['images'] = array();
            $originalWriteArray = & $this->_writeArray;
            $this->_writeArray = & $returnArray['images'];
            $product->load('media_gallery');
            $mediaGalleryImages = $product->getMediaGalleryImages();
            foreach ($mediaGalleryImages as $mediaGalleryImage) {
                $this->_writeArray = & $returnArray['images'][];
                foreach ($mediaGalleryImage->getData() as $key => $value) {
                    $this->writeValue($key, $value);
                }
            }
            $this->_writeArray = & $originalWriteArray;
        }
    }

    private function _getPrice($product)
    {
        $price = $product->getFinalPrice();
        if ($price == 0) {
            $price = $product->getMinPrice();
        }
        $price = $this->_addTax($product, $price, 'price');
        return $price;
    }

    private function _addTax($product, $price, $key)
    {
        $taxPercent = false;
        if ($product->getTaxPercent()) {
            $taxPercent = $product->getTaxPercent();
        } else {
            $taxPercent = false;
            if ($product->getTypeId() == 'grouped') {
                // Get tax_percent from child product
                $childProductIds = $product->getTypeInstance()->getChildrenIds($product->getId());
                if (is_array($childProductIds)) {
                    $childProductIds = array_shift($childProductIds);
                    if (is_array($childProductIds)) {
                        $childProductId = array_shift($childProductIds);
                        $childProduct = Mage::getModel('catalog/product')->load($childProductId);
                        if ($childProduct->getId()) {
                            $request = Mage::getSingleton('tax/calculation')->getRateRequest(false, false, false, $product->getStore());
                            $taxPercent = Mage::getSingleton('tax/calculation')->getRate($request->setProductClassId($childProduct->getTaxClassId()));
                        }
                    }
                }
            }
        }
        if ($taxPercent > 0) {
            if (!$this->_config['including_tax']) {
                // Write price excl. tax
                $this->writeValue($key . '_excl_tax', $price);
                // Prices are excluding tax -> add tax
                $price *= 1 + $taxPercent / 100;
            } else {
                // Prices are including tax - do not add tax to price
                // Write price excl. tax
                $this->writeValue($key . '_excl_tax', $price / (1 + $taxPercent / 100));
            }
        } else {
            $this->writeValue($key . '_excl_tax', $price);
        }
        return $price;
    }
}