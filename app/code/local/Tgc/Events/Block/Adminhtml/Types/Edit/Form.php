<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Events_Block_Adminhtml_Types_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

	protected function _prepareForm()
	{
		$item = Mage::registry('types_data');
		if ($item)
		{
			$data = $item->getData();
		}
		else
		{
			$data = array();
		}

		$form = new Varien_Data_Form(array(
			'id' => 'edit_form',
			'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
			'method' => 'post',
			'enctype' => 'multipart/form-data',
		));

		$form->setUseContainer(true);

		$this->setForm($form);

		$fieldset = $form->addFieldset('event_type_form', array(
			'legend' => Mage::helper('events')->__('Type Information')
		));

		$fieldset->addField('type', 'text', array(
			'label'     => Mage::helper('events')->__('Type'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'type',
		));

        $fieldset->addField('type_icon', 'image', array(
            'label'     => Mage::helper('events')->__('Image'),
            'required'  => false,
            'name'      => 'type_icon',
            'note'	  => Mage::helper('events')->__('<p>(upload image files only)</p>')
        ));


        $fieldset->addField('sort_order', 'text', array(
            'label'     => Mage::helper('events')->__('Sort Order'),
            'name'      => 'sort_order'
        ));

        $fieldset->addField('is_active', 'select', array(
			'label'     => Mage::helper('events')->__('Status'),
			'name'      => 'is_active',
			'values'    => Mage::getModel('adminhtml/system_config_source_enabledisable')->toOptionArray(),
		));

		$form->setValues($data);

		return parent::_prepareForm();
	}

}