<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2013-02-10T18:07:18+01:00
 * File:          app/code/local/Xtento/ProductExport/Block/Adminhtml/Destination.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Block_Adminhtml_Destination extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'xtento_productexport';
        $this->_controller = 'adminhtml_destination';
        $this->_headerText = Mage::helper('xtento_productexport')->__('Product Export - Destinations');
        $this->_addButtonLabel = Mage::helper('xtento_productexport')->__('Add New Destination');
        parent::__construct();
    }

    protected function _toHtml()
    {
        return $this->getLayout()->createBlock('xtento_productexport/adminhtml_widget_menu')->toHtml() . parent::_toHtml();
    }
}