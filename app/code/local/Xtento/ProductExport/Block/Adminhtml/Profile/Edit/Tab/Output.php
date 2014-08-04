<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2013-03-24T18:22:12+01:00
 * File:          app/code/local/Xtento/ProductExport/Block/Adminhtml/Profile/Edit/Tab/Output.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Block_Adminhtml_Profile_Edit_Tab_Output extends Xtento_ProductExport_Block_Adminhtml_Widget_Tab
{
    protected function getFormMessages()
    {
        $formMessages = array();
        $formMessages[] = array('type' => 'notice', 'message' => Mage::helper('xtento_productexport')->__('The XSL Template "translates" the internal Magento database format into your required output format. You can find more information about XSL Templates in our <a href="http://support.xtento.com/wiki/Magento_Extensions:Magento_Product_Export_Module" target="_blank">support wiki</a>. If you don\'t want to create the XSL Template yourself, please refer to our <a href="http://www.xtento.com/magento-services/xsl-template-creation-service.html" target="_blank">XSL Template Creation Service</a>.'));
        return $formMessages;
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $this->setTemplate('xtento/productexport/output.phtml');
        return parent::_prepareForm();
    }

    protected function getTestEntityId()
    {
        $profile = Mage::registry('product_export_profile');
        if (!$profile->getEntity()) {
            return '';
        }
        $testId = $profile->getTestId();
        if (!$testId || $testId == 0) {
            return Mage::helper('xtento_productexport/export')->getLastEntityId($profile->getEntity());
        } else {
            return $testId;
        }
    }

    protected function getXslTemplate()
    {
        return htmlspecialchars(Mage::registry('product_export_profile')->getXslTemplate(), ENT_NOQUOTES);
    }
}