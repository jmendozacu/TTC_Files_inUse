<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2013-03-29T17:49:29+01:00
 * File:          app/code/local/Xtento/ProductExport/Model/Export/Entity/Collection/Item.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Model_Export_Entity_Collection_Item extends Varien_Object
{
    private $_collectionItem;

    public function __construct($collectionItem, $entityType, $currItemNo, $collectionCount)
    {
        $this->_collectionItem = $collectionItem;
        $this->_collectionSize = $collectionCount;
        $this->_currItemNo = $currItemNo;
        if ($entityType == Xtento_ProductExport_Model_Export::ENTITY_PRODUCT) {
            $this->setProduct($collectionItem);
        }
        if ($entityType == Xtento_ProductExport_Model_Export::ENTITY_CATEGORY) {
            $this->setCategory($collectionItem);
        }
    }

    public function getObject() {
        return $this->_collectionItem;
    }
}