<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Events_Block_Adminhtml_Locations_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

	protected function _prepareForm()
	{
		$location = Mage::registry('locations_data');
		if ($location)
		{
			$data = $location->getData();
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

		$fieldset = $form->addFieldset('event_location_form', array(
			'legend' => Mage::helper('events')->__('Location Information')
		));

		$fieldset->addField('location', 'text', array(
			'label'     => Mage::helper('events')->__('Location'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'location',
			//'note'      => Mage::helper('events')->__('The name of the sales engineer.'),
		));

		/*$fieldset->addField('user_id', 'select', array(
			'label'     => Mage::helper('events')->__('User'),
			'name'      => 'user_id',
			'required'  => true,
			'values'    => $this->_getUserValues(),
			'note'      => Mage::helper('events')->__('User associated to this sales engineer'),
		));*/

        $fieldset->addField('location_code', 'text', array(
            'label'     => Mage::helper('events')->__('URL Code'),
            'required'  => true,
            'name'      => 'location_code',
			'note'      => Mage::helper('events')->__('No spaces, please use dashes (-) or underscores (_)'),
        ));

        $fieldset->addField('location_image', 'image', array(
            'label'     => Mage::helper('events')->__('Image'),
            'required'  => false,
            'name'      => 'location_image',
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

	protected function _getUserValues()
	{
		$users = array();
		/* @var $collection Mage_Admin_Model_Resource_User_Collection */
		$collection = Mage::getModel('admin/user')->getCollection();
		foreach($collection as $user)
		{
			/* @var $user Mage_Admin_Model_User */
			$users[] = array(
				'value' => $user->getId(),
				'label' => $user->getName() . ' ('.$user->getUsername().')'
			);
		}
		return $users;
	}
}