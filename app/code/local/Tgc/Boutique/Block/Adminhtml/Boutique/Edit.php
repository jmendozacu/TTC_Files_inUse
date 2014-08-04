<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Block_Adminhtml_Boutique_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId   = 'id';
        $this->_blockGroup = 'tgc_boutique';
        $this->_controller = 'adminhtml_boutique';

        $this->_updateButton('save', 'label', Mage::helper('tgc_boutique')->__('Save Boutique'));
        $this->_updateButton('delete', 'label', Mage::helper('tgc_boutique')->__('Delete Boutique'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('tgc_boutique')->__('Save And Continue Edit'),
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
        if (Mage::registry('boutique_data') && Mage::registry('boutique_data')->getId()) {
            $name = Mage::registry('boutique_data')->getName();
            return Mage::helper('tgc_boutique')->__("Edit Boutique '%s'", $name);
        } else {
            return Mage::helper('tgc_boutique')->__('Add Boutique');
        }
    }
}
