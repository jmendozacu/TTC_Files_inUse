<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2013-03-24T17:56:55+01:00
 * File:          app/code/local/Xtento/ProductExport/Model/Output/Abstract.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

abstract class Xtento_ProductExport_Model_Output_Abstract extends Mage_Core_Model_Abstract implements Xtento_ProductExport_Model_Output_Interface
{
    static $iteratingKeys = array('items', 'custom_options', 'product_attributes', 'product_options');

    protected function _replaceFilenameVariables($filename, $exportArray)
    {
        $filename = str_replace("|", "-", $filename); // Remove the pipe character - it's not allowed in file names anyways and we use it to separate multiple files in the DB
        // Replace variables in filename
        $replaceableVariables = array(
            '/%d%/' => Mage::getSingleton('core/date')->date('d'),
            '/%m%/' => Mage::getSingleton('core/date')->date('m'),
            '/%y%/' => Mage::getSingleton('core/date')->date('y'),
            '/%Y%/' => Mage::getSingleton('core/date')->date('Y'),
            '/%h%/' => Mage::getSingleton('core/date')->date('H'),
            '/%i%/' => Mage::getSingleton('core/date')->date('i'),
            '/%s%/' => Mage::getSingleton('core/date')->date('s'),
            '/%lastentityid%/' => $this->getVariableValue('last_entity_id', $exportArray),
            '/%collectioncount%/' => $this->getVariableValue('collection_count', $exportArray),
            '/%uuid%/' => uniqid(),
            '/%exportid%/' => $this->getVariableValue('export_id', $exportArray),
        );
        $filename = preg_replace(array_keys($replaceableVariables), array_values($replaceableVariables), $filename);
        return $filename;
    }

    protected function getVariableValue($variable, $exportArray)
    {
        $arrayToWorkWith = $exportArray;
        if ($variable == 'export_id') {
            if (Mage::registry('product_export_log')) {
                return Mage::registry('product_export_log')->getId();
            } else {
                return 0;
            }
        }
        if ($variable == 'collection_count') {
            return count($arrayToWorkWith);
        }
        if ($variable == 'total_item_count') {
            $totalItemCount = 0;
            foreach ($arrayToWorkWith as $collectionObject) {
                if (isset($collectionObject['items'])) {
                    foreach ($collectionObject['items'] as $item) {
                        $totalItemCount++;
                    }
                }
            }
            return $totalItemCount;
        }
        if ($variable == 'last_entity_id') {
            $lastItem = array_pop($arrayToWorkWith);
            if (isset($lastItem['entity_id'])) {
                return $lastItem['entity_id'];
            }
        }
        if ($variable == 'date_from_timestamp') {
            $firstObject = array_shift($arrayToWorkWith);
            return Mage::helper('xtento_productexport/date')->convertDateToStoreTimestamp($firstObject['created_at']);
        }
        if ($variable == 'date_to_timestamp') {
            $lastObject = array_pop($arrayToWorkWith);
            return Mage::helper('xtento_productexport/date')->convertDateToStoreTimestamp($lastObject['created_at']);
        }
        return '';
    }

    protected function _throwXmlException($message)
    {
        $message .= "\n";
        foreach (libxml_get_errors() as $error) {
            $message .= "\tLine " . $error->line . ": " . $error->message;
            if (strpos($error->message, "\n") === FALSE) {
                $message .= "\n";
            }
        }
        libxml_clear_errors();
        Mage::throwException($message);
    }

    protected function _changeEncoding($input, $encoding)
    {
        $output = $input;
        if (!empty($encoding) && @function_exists('iconv')) {
            $output = @iconv("UTF-8", $encoding, $input);
            if (!$output) {
                // Error
            }
        }
        return $output;
    }
}