<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Block_Adminhtml_BoutiquePages_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId   = 'id';
        $this->_blockGroup = 'tgc_boutique';
        $this->_controller = 'adminhtml_boutiquePages';

        $this->_updateButton('save', 'label', Mage::helper('tgc_boutique')->__('Save Boutique Page'));
        $this->_updateButton('delete', 'label', Mage::helper('tgc_boutique')->__('Delete Boutique Page'));

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
        if (Mage::registry('boutiquePages_data') && Mage::registry('boutiquePages_data')->getId()) {
            $title = Mage::registry('boutiquePages_data')->getPageTitle();
            return Mage::helper('tgc_boutique')->__("Edit Boutique Page '%s'", $title);
        } else {
            return Mage::helper('tgc_boutique')->__('Add Boutique Page');
        }
    }
}
