<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2013-02-10T18:06:03+01:00
 * File:          app/code/local/Xtento/ProductExport/Block/Adminhtml/Log/Grid/Renderer/Destination.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Block_Adminhtml_Log_Grid_Renderer_Destination extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    static $destinations = array();

    public function render(Varien_Object $row)
    {
        $destinationIds = $row->getDestinationIds();
        $destinationText = "";
        if (empty($destinationIds)) {
            return Mage::helper('xtento_productexport')->__('No destination saved.');
        }
        foreach (explode("&", $destinationIds) as $destinationId) {
            if (!empty($destinationId) && is_numeric($destinationId)) {
                if (!isset(self::$destinations[$destinationId])) {
                    $destination = Mage::getModel('xtento_productexport/destination')->load($destinationId);
                    self::$destinations[$destinationId] = $destination;
                } else {
                    $destination = self::$destinations[$destinationId];
                }
                if ($destination->getId()) {
                    $destinationText .= $destination->getName() . " (".Mage::getSingleton('xtento_productexport/system_config_source_destination_type')->getName($destination->getType()).")<br>";
                }
            }
        }
        return $destinationText;
    }
}