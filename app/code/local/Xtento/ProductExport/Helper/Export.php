<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2013-03-24T18:23:58+01:00
 * File:          app/code/local/Xtento/ProductExport/Helper/Export.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Helper_Export extends Mage_Core_Helper_Abstract
{
    public function getExportEntity($entity)
    {
        if ($entity == Xtento_ProductExport_Model_Export::ENTITY_PRODUCT) {
            return 'catalog/product';
        } else if ($entity == Xtento_ProductExport_Model_Export::ENTITY_CATEGORY) {
            return 'catalog/category';
        }
        Mage::throwException(Mage::helper('xtento_productexport')->__('Could not find export entity "%s"', $entity));
    }

    public function getLastEntityId($entity)
    {
        $collection = Mage::getModel($this->getExportEntity($entity))->getCollection()
            ->addAttributeToSelect('entity_id');
        $collection->getSelect()->limit(1)->order('entity_id DESC');
        $object = $collection->getFirstItem();
        return $object->getId();
    }

    public function getExportBkpDir()
    {
        return Mage::getBaseDir('var') . DS . "product_export_bkp" . DS;
    }
}