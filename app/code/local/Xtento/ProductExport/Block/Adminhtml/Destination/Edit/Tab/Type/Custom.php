<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2013-07-01T23:20:39+02:00
 * File:          app/code/local/Xtento/ProductExport/Block/Adminhtml/Destination/Edit/Tab/Type/Custom.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Block_Adminhtml_Destination_Edit_Tab_Type_Custom
{
    // Custom Type Configuration
    public function getFields($form)
    {
        $fieldset = $form->addFieldset('config_fieldset', array(
            'legend' => Mage::helper('xtento_productexport')->__('Custom Type Configuration'),
            'class' => 'fieldset-wide'
        ));

        $fieldset->addField('custom_class', 'text', array(
            'label' => Mage::helper('xtento_productexport')->__('Custom Class Identifier'),
            'name' => 'custom_class',
            'note' => Mage::helper('xtento_productexport')->__('You can set up an own class in our (or another) module which gets called when exporting. The saveFiles($fileArray ($filename => $contents)) function would be called in your class. If your class was called Xtento_ProductExport_Model_Destination_Myclass then the identifier to enter here would be xtento_productexport/destination_myclass'),
            'required' => true
        ));
    }
}