<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2013-02-10T18:06:03+01:00
 * File:          app/code/local/Xtento/ProductExport/Model/Output/Csv.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Model_Output_Csv extends Xtento_ProductExport_Model_Output_Abstract
{
    public function convertData($exportArray)
    {
        return array(); // Not yet implemented.
        /*
        // Convert to XML first
        $convertedData = Mage::getModel('xtento_productexport/output_xml', array('profile' => $this->getProfile()))->convertData($exportArray);
        // Get "first" file from returned data.
        $convertedXml = array_pop($convertedData);

        $filename = $this->_replaceFilenameVariables($profile->getFilename(), $exportArray);
        $charsetEncoding = $profile->getEncoding();
        $outputXml = $this->_changeEncoding($outputXml, $charsetEncoding);
        $outputData[$filename] = $outputXml;

        // Return data
        return $outputData;
        */
    }
}