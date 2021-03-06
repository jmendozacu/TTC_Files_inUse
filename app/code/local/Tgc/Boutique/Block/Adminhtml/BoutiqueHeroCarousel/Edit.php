<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @boutique    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Block_Adminhtml_BoutiqueHeroCarousel_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'tgc_boutique';
        $this->_controller = 'adminhtml_boutiqueHeroCarousel';

        $this->_updateButton('save', 'label', Mage::helper('tgc_boutique')->__('Save Carousel Item'));
        $this->_updateButton('delete', 'label', Mage::helper('tgc_boutique')->__('Delete Carousel Item'));

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
        if (Mage::registry('boutiqueHeroCarousel_data') && Mage::registry('boutiqueHeroCarousel_data')->getId()) {
            $itemId = Mage::registry('boutiqueHeroCarousel_data')->getId();
            return Mage::helper('tgc_boutique')->__("Edit Carousel Item '%s'", $itemId);
        } else {
            return Mage::helper('tgc_boutique')->__('Add Carousel Item');
        }
    }
}
