<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2013-07-29T15:37:16+02:00
 * File:          app/code/local/Xtento/ProductExport/Helper/Entity.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Helper_Entity extends Mage_Core_Helper_Abstract
{
    public function getPluralEntityName($entity) {
        if ($entity == Xtento_ProductExport_Model_Export::ENTITY_CATEGORY) {
            return "categories";
        }
        if ($entity == Xtento_ProductExport_Model_Export::ENTITY_PRODUCT) {
            return "products";
        }
        return $entity;
    }
}