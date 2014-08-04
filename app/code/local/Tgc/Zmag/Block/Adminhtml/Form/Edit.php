<?php
/**
 * User: mhidalgo
 * Date: 11/03/14
 * Time: 16:46
 */
class Tgc_Zmag_Block_Adminhtml_Form_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'zmag_id';
        $this->_blockGroup = 'tgc_zmag';
        $this->_controller = 'adminhtml_form';

        $this->_updateButton('save', 'label', Mage::helper('tgc_zmag')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('tgc_zmag')->__('Delete'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = '
            function saveAndContinueEdit(){
                editForm.submit($("edit_form").action+"back/edit/");
            }

            document.observe("dom:loaded", function() {
                if (!$("icon_image")){
                    $("icon").addClassName("required-entry");
                }
            });
        ';
    }

    public function getHeaderText()
    {
        if (Mage::registry('zmag_data') && Mage::registry('zmag_data')->getId())
        {
            return Mage::helper('tgc_zmag')->__('Edit Zmag');
        } else {
            return Mage::helper('tgc_zmag')->__('New Zmag');
        }
    }
}