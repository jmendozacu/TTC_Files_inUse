<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2013-03-24T17:55:07+01:00
 * File:          app/code/local/Xtento/ProductExport/Model/Export/Entity/Category.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Model_Export_Entity_Category extends Xtento_ProductExport_Model_Export_Entity_Abstract
{
    protected $_entityType = Xtento_ProductExport_Model_Export::ENTITY_CATEGORY;

    protected function _construct()
    {
        $collection = Mage::getResourceModel('catalog/category_collection')
            ->addAttributeToSelect('*');
        $this->_collection = $collection;
        parent::_construct();
    }
}