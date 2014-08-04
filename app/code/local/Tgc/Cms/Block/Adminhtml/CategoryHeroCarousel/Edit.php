<?php
/**
 * User: mhidalgo
 * Date: 14/03/14
 * Time: 09:37
 */

class Tgc_Cms_Block_Adminhtml_CategoryHeroCarousel_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    public function __construct()
    {
        parent::__construct();

        $this->_objectId   = 'id';
        $this->_blockGroup = 'tgc_cms';
        $this->_controller = 'adminhtml_categoryHeroCarousel';

        $this->_updateButton('save', 'label', Mage::helper('tgc_cms')->__('Save Carousel Item'));
        $this->_updateButton('delete', 'label', Mage::helper('tgc_cms')->__('Delete Carousel Item'));

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
        if (Mage::registry('categoryHeroCarousel_data') && Mage::registry('categoryHeroCarousel_data')->getId()) {
            $itemId = Mage::registry('categoryHeroCarousel_data')->getId();
            return Mage::helper('tgc_cms')->__("Edit Carousel Item '%s'", $itemId);
        } else {
            return Mage::helper('tgc_cms')->__('Add Carousel Item');
        }
    }
}