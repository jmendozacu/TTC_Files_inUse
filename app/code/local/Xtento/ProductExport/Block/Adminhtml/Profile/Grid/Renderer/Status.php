<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2013-02-10T18:06:03+01:00
 * File:          app/code/local/Xtento/ProductExport/Block/Adminhtml/Profile/Grid/Renderer/Status.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Block_Adminhtml_Profile_Grid_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        if ($row->getEnabled() == 0) {
            return '<span class="grid-severity-critical"><span>' . Mage::helper('xtento_productexport')->__('Disabled') . '</span></span>';
        } else if ($row->getEnabled() == 1) {
            return '<span class="grid-severity-notice"><span>' . Mage::helper('xtento_productexport')->__('Enabled') . '</span></span>';
        }
    }
}