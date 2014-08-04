<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2014-03-13T21:27:48+01:00
 * File:          app/code/local/Xtento/ProductExport/Model/Export/Entity/Product.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Model_Export_Entity_Product extends Xtento_ProductExport_Model_Export_Entity_Abstract
{
    protected $_entityType = Xtento_ProductExport_Model_Export::ENTITY_PRODUCT;

    protected function _construct()
    {
        $collection = Mage::getResourceModel('catalog/product_collection');
        #    ->addAttributeToSelect('*');
        #$collection->getSelect()->distinct();
        $collection->joinField('qty',
            'cataloginventory/stock_item',
            'qty',
            'product_id=entity_id',
            '{{table}}.stock_id=1',
            'left');
        #$collection->getSelect()->group('e.entity_id');
        #var_dump($collection->count());
        #echo $collection->getSelect(); die();
        #->joinTable('cataloginventory/stock_item', 'product_id=entity_id', array('stock_status'));
        $collection->addTaxPercents();
        #$collection->addUrlRewrite();

        $this->_collection = $collection;
        parent::_construct();
    }

    public function runExport()
    {
        if ($this->getProfile()) {
            if ($this->getProfile()->getStoreIds()) {
                $this->_collection->getSelect()->joinLeft(
                    Mage::getSingleton('core/resource')->getTableName('catalog_product_index_price') . ' AS price_index',
                    'price_index.entity_id=e.entity_id AND customer_group_id=0 AND price_index.website_id=' . Mage::getModel('core/store')->load($this->getProfile()->getStoreIds())->getWebsiteId(),
                    array(
                        'min_price' => 'min_price',
                        'max_price' => 'max_price',
                        'tier_price' => 'tier_price',
                        'final_price' => 'final_price'
                    )
                );
                $this->_collection->addStoreFilter($this->getProfile()->getStoreIds());
                $this->_collection-> /*setStore($this->getProfile()->getStoreIds())->addWebsiteFilter(Mage::app()->getStore($this->getProfile()->getStoreIds())->getWebsiteId())->*/
                    addAttributeToSelect("tax_class_id");
            }
            if ($this->getProfile()->getOutputType() == 'csv' || $this->getProfile()->getOutputType() == 'xml') {
                // Fetch all fields
                $this->_collection->addAttributeToSelect('*');
            } else {
                $attributesToSelect = explode(",", $this->getProfile()->getAttributesToSelect());
                if (empty($attributesToSelect) || (isset($attributesToSelect[0]) && empty($attributesToSelect[0]))) {
                    $attributes = '*';
                } else {
                    // Get all attributes which should be always fetched
                    $attributes = array('entity_id', 'sku', 'price', 'name', 'status', 'url_key', 'type_id', 'image');
                    $attributes = array_merge($attributes, $attributesToSelect);
                    $attributes = array_unique($attributes);
                }
                $this->_collection->addAttributeToSelect($attributes);
            }
            #echo($this->_collection->getSelect());
            if ($this->getProfile()->getExportFilterProductVisibility() != '') {
                $this->_collection->addAttributeToFilter(
                    'visibility',
                    array('in' => explode(",", $this->getProfile()->getExportFilterProductVisibility()))
                );
            }
            if ($this->getProfile()->getExportFilterInstockOnly() === "1") {
                Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($this->_collection);
            }
        }
        return $this->_runExport();
    }

    protected function _runExport()
    {
        $hiddenProductTypes = explode(",", $this->getProfile()->getExportFilterProductType());
        if (!empty($hiddenProductTypes)) {
            $this->_collection->addAttributeToFilter('type_id', array('nin' => $hiddenProductTypes));
        }
        return parent::_runExport();
    }
}