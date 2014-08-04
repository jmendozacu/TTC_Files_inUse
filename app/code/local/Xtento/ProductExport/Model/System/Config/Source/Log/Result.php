<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2013-02-10T18:06:03+01:00
 * File:          app/code/local/Xtento/ProductExport/Model/System/Config/Source/Log/Result.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Model_System_Config_Source_Log_Result
{
    public function toOptionArray()
    {
        $values = array();
        $values[Xtento_ProductExport_Model_Log::RESULT_NORESULT] = Mage::helper('xtento_productexport')->__('No Result');
        $values[Xtento_ProductExport_Model_Log::RESULT_SUCCESSFUL] = Mage::helper('xtento_productexport')->__('Successful');
        $values[Xtento_ProductExport_Model_Log::RESULT_WARNING] = Mage::helper('xtento_productexport')->__('Warning');
        $values[Xtento_ProductExport_Model_Log::RESULT_FAILED] = Mage::helper('xtento_productexport')->__('Failed');
        return $values;
    }
}