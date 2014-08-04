<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Block_Adminhtml_CustomerUpsell_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId   = 'id';
        $this->_blockGroup = 'tgc_datamart';
        $this->_controller = 'adminhtml_customerUpsell';

        $this->_updateButton('save', 'label', Mage::helper('tgc_datamart')->__('Save Customer Upsell'));
        $this->_updateButton('delete', 'label', Mage::helper('tgc_datamart')->__('Delete Customer Upsell'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('tgc_datamart')->__('Save And Continue Edit'),
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
        if (Mage::registry('customerUpsell_data') && Mage::registry('customerUpsell_data')->getId()) {
            $upsellId = Mage::registry('customerUpsell_data')->getId();
            return Mage::helper('tgc_datamart')->__("Edit Customer Upsell '%s'", $upsellId);
        } else {
            return Mage::helper('tgc_datamart')->__('Add Customer Upsell');
        }
    }
}
