<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_Adminhtml_MergeAccounts_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId   = 'id';
        $this->_blockGroup = 'tgc_dl';
        $this->_controller = 'adminhtml_mergeAccounts';

        $this->_updateButton('save', 'label', Mage::helper('tgc_dl')->__('Save Merged Account'));
        $this->_updateButton('delete', 'label', Mage::helper('tgc_dl')->__('Delete Merged Account'));

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
        if (Mage::registry('mergeAccounts_data') && Mage::registry('mergeAccounts_data')->getId()) {
            $id = Mage::registry('mergeAccounts_data')->getId();
            return Mage::helper('tgc_dl')->__("Edit Merged Account '%s'", $id);
        } else {
            return Mage::helper('tgc_dl')->__('Add Merged Account');
        }
    }
}
