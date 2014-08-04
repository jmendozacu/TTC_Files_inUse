<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_Adminhtml_AkamaiContent_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId   = 'id';
        $this->_blockGroup = 'tgc_dl';
        $this->_controller = 'adminhtml_akamaiContent';

        $this->_updateButton('save', 'label', Mage::helper('tgc_dl')->__('Save Akamai Content'));
        $this->_updateButton('delete', 'label', Mage::helper('tgc_dl')->__('Delete Akamai Content'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('tgc_dl')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('form_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'edit_form');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'edit_form');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('akamaiContent_data') && Mage::registry('akamaiContent_data')->getId()) {
            $id = Mage::registry('akamaiContent_data')->getId();
            return Mage::helper('tgc_dl')->__("Edit Akamai Content '%s'", $id);
        } else {
            return Mage::helper('tgc_dl')->__('Add Akamai Content');
        }
    }
}
