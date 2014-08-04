<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2014-02-13T20:35:47+01:00
 * File:          app/code/local/Xtento/ProductExport/Model/Export/Entity/Abstract.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

abstract class Xtento_ProductExport_Model_Export_Entity_Abstract extends Mage_Core_Model_Abstract
{
    protected $_collection;
    private $_returnArray = array();

    protected function _construct()
    {
        parent::_construct();
    }

    protected function _runExport()
    {
        if ($this->getProfile()->getEntity() == Xtento_ProductExport_Model_Export::ENTITY_PRODUCT && $this->getProfile()->getStoreIds() !== '') {
            $this->_collection->addStoreFilter($this->getProfile()->getStoreIds());
        }
        // Reset export classes
        Mage::getSingleton('xtento_productexport/export_data')->resetExportClasses();
        // Register rule information for catalog rules
        $storeId = 0;
        if ($this->getProfile()->getStoreIds()) {
            $storeId = $this->getProfile()->getStoreIds();
        }
        $productStore = Mage::getModel('core/store')->load($storeId);
        if ($productStore) {
            if (Mage::registry('rule_data') !== null) {
                Mage::unregister('rule_data');
            }
            Mage::register('rule_data', new Varien_Object(array(
                'store_id' => $storeId,
                'website_id' => $productStore->getWebsiteId(),
                'customer_group_id' => 0, // NOT_LOGGED_IN
            )));
        }
        // Get export fields
        $exportFields = array(); // Deprecated
        $originalCollection = $this->_collection;
        $collectionCount = null;
        $currItemNo = 1;
        $currPage = 1;
        $lastPage = 0;
        $break = false;
        while ($break !== true) {
            $collection = clone $originalCollection;
            $collection->setPageSize(100); // If just 100 items are returned with every export, something is wrong with getLastPageNumber()
            $collection->setCurPage($currPage);
            $collection->load();
            if (is_null($collectionCount)) {
                $collectionCount = $collection->getSize();
                $lastPage = $collection->getLastPageNumber();
            }
            if ($currPage == $lastPage) {
                $break = true;
            }
            $currPage++;
            foreach ($collection as $collectionItem) {
                #var_dump("validation result: ".$this->getProfile()->validate($collectionItem));
                if ($this->getExportType() == Xtento_ProductExport_Model_Export::EXPORT_TYPE_TEST || $this->getProfile()->validate($collectionItem)) {
                    $returnData = $this->_exportData(new Xtento_ProductExport_Model_Export_Entity_Collection_Item($collectionItem, $this->_entityType, $currItemNo, $collectionCount), $exportFields);
                    if (!empty($returnData)) {
                        $this->_returnArray[] = $returnData;
                        $currItemNo++;
                    }
                }
            }
        }
        #var_dump($this->_returnArray); die();
        return $this->_returnArray;
    }

    public function setCollectionFilters($filters)
    {
        if (is_array($filters)) {
            foreach ($filters as $filter) {
                foreach ($filter as $attribute => $filterArray) {
                    $this->_collection->addAttributeToFilter($attribute, $filterArray);
                }
            }
        }
        return $this->_collection;
    }

    protected function _exportData($collectionItem, $exportFields)
    {
        return Mage::getSingleton('xtento_productexport/export_data')
            ->setShowEmptyFields($this->getShowEmptyFields())
            ->setProfile($this->getProfile() ? $this->getProfile() : new Varien_Object())
            ->setExportFields($exportFields)
            ->getExportData($this->_entityType, $collectionItem);
    }

    public function runExport()
    {
        return $this->_runExport();
    }
}