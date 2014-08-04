<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2013-02-10T18:57:17+01:00
 * File:          app/code/local/Xtento/ProductExport/Block/Adminhtml/Profile/Edit.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Block_Adminhtml_Profile_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'xtento_productexport';
        $this->_controller = 'adminhtml_profile';

        if (Mage::registry('product_export_profile')->getId()) {
            $this->_addButton('duplicate_button', array(
                'label' => Mage::helper('xtento_productexport')->__('Duplicate Profile'),
                'onclick' => 'setLocation(\'' . $this->getUrl('*/*/duplicate', array('_current' => true)) . '\')',
                'class' => 'add',
            ), 0);

            $this->_addButton('export_button', array(
                'label' => Mage::helper('xtento_productexport')->__('Export Profile'),
                'onclick' => 'setLocation(\'' . $this->getUrl('*/productexport_manual/index', array('profile_id' => Mage::registry('product_export_profile')->getId())) . '\')',
                'class' => 'go',
            ), 0);

            $this->_updateButton('save', 'label', Mage::helper('xtento_productexport')->__('Save Profile'));
            $this->_updateButton('delete', 'label', Mage::helper('xtento_productexport')->__('Delete Profile'));
            $this->_removeButton('reset');
        } else {
            $this->_removeButton('delete');
            $this->_removeButton('save');
        }

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('xtento_productexport')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                if (editForm && editForm.validator.validate()) {
                    Element.show('loading-mask');
                    setLoaderPosition();
                    var tabsIdValue = profile_tabsJsTabs.activeTab.id;
                    var tabsBlockPrefix = 'profile_tabs_';
                    if (tabsIdValue.startsWith(tabsBlockPrefix)) {
                        tabsIdValue = tabsIdValue.substr(tabsBlockPrefix.length)
                    }
                }
                editForm.submit($('edit_form').action+'continue/edit/active_tab/'+tabsIdValue);
            }
            varienGlobalEvents.attachEventHandler('formSubmit', function(){
                if (editForm && editForm.validator.validate()) {
                    Element.show('loading-mask');
                    setLoaderPosition();
                }
            });
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('product_export_profile')->getId()) {
            return Mage::helper('xtento_productexport')->__('Edit ' . ucfirst(Mage::registry('product_export_profile')->getEntity()) . ' Export Profile \'%s\'', Mage::helper('xtcore/core')->escapeHtml(Mage::registry('product_export_profile')->getName()));
        } else {
            return Mage::helper('xtento_productexport')->__('New Profile');
        }
    }

    protected function _toHtml()
    {
        return $this->getLayout()->createBlock('xtento_productexport/adminhtml_widget_menu')->setShowWarning(1)->toHtml() . parent::_toHtml();
    }
}