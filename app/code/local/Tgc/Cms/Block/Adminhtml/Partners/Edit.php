<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Block_Adminhtml_Partners_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId   = 'id';
        $this->_blockGroup = 'tgc_cms';
        $this->_controller = 'adminhtml_partners';

        $this->_updateButton('save', 'label', Mage::helper('tgc_cms')->__('Save Partners Item'));
        $this->_updateButton('delete', 'label', Mage::helper('tgc_cms')->__('Delete Partners Item'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('tgc_cms')->__('Save And Continue Edit'),
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
        if (Mage::registry('partners_data') && Mage::registry('partners_data')->getId()) {
            $itemId = Mage::registry('partners_data')->getId();
            return Mage::helper('tgc_cms')->__("Edit Partners Item '%s'", $itemId);
        } else {
            return Mage::helper('tgc_cms')->__('Add Partner Item');
        }
    }
}
