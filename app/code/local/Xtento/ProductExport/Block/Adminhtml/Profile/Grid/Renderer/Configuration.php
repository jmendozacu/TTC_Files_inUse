<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2013-02-10T18:06:04+01:00
 * File:          app/code/local/Xtento/ProductExport/Block/Adminhtml/Profile/Grid/Renderer/Configuration.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Block_Adminhtml_Profile_Grid_Renderer_Configuration extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $configuration = array();
        $configuration['Cronjob Export'] = ($row->getCronjobEnabled()) ? Mage::helper('xtento_productexport')->__('Enabled') : Mage::helper('xtento_productexport')->__('Disabled');
        $configuration['Event Export'] = ($row->getEventObservers() !== '') ? Mage::helper('xtento_productexport')->__('Enabled') : Mage::helper('xtento_productexport')->__('Disabled');
        if (!empty($configuration)) {
            $configurationHtml = '';
            foreach ($configuration as $key => $value) {
                $configurationHtml .= Mage::helper('xtento_productexport')->__($key).': <i>'.$value.'</i><br/>';
            }
            return $configurationHtml;
        } else {
            return '---';
        }
    }
}