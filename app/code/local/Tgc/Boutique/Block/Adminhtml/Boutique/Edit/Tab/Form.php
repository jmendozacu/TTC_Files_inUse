<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Block_Adminhtml_Boutique_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $data = array();
        if (Mage::getSingleton('adminhtml/session')->getBoutiqueFormData()) {
            $data = Mage::getSingleton('adminhtml/session')->getBoutiqueFormData();
            Mage::getSingleton('adminhtml/session')->setBoutiqueFormData(null);
        } elseif (Mage::registry('boutique_data')) {
            $data = Mage::registry('boutique_data')->getData();
        }

        $isDefault = (isset($data['is_default']) && $data['is_default'] == 1);

        $fieldset = $form->addFieldset(
            'boutique_form',
            array('legend' => Mage::helper('tgc_boutique')->__('Boutique Information'))
        );

        $fieldset->addField('is_default', 'select', array(
            'label'     => Mage::helper('tgc_boutique')->__('Is Default'),
            'name'      => 'is_default',
            'values'    => $isDefault ? array(array('value' => 1, 'label' => 'Yes')) : Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));

        $fieldset->addField('disable_carousel', 'select', array(
            'label'     => Mage::helper('tgc_boutique')->__('Disable Hero Carousel for this Boutique?'),
            'name'      => 'disable_carousel',
            'values'    => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));

        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('tgc_boutique')->__('Boutique Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'name',
        ));

        $fieldset->addField('url_key', 'text', array(
            'label'     => Mage::helper('tgc_boutique')->__('Boutique URL key'),
            'class'     => 'required-entry validate-boutique-identifier',
            'required'  => true,
            'name'      => 'url_key',
        ));

        $fieldset->addField('pages', 'multiselect', array(
            'label'     => Mage::helper('tgc_boutique')->__('Boutique Pages'),
            'required'  => true,
            'name'      => 'pages',
            'values'    => Mage::getModel('tgc_boutique/source_pages')->toOptionArray(),
        ));

        $form->setValues($data);

        return parent::_prepareForm();
    }
}
