<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Events_Block_Adminhtml_Locations_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

	public function __construct()
	{
		parent::__construct();

		$this->_objectId = 'id';
		// $this->_blockGroup . '/' . $this->_controller . '_' . $this->_mode . '_form'
		$this->_blockGroup = 'tgc_events';
		$this->_controller = 'adminhtml_locations';
		$this->_mode = 'edit';

		$this->_addButton('save_and_continue', array(
			'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
			'onclick' => 'saveAndContinueEdit()',
			'class' => 'save',
		), -100);
		$this->_updateButton('save', 'label', Mage::helper('events')->__('Save'));

		$this->_formScripts[] = "
                            function saveAndContinueEdit(){
                                editForm.submit($('edit_form').action+'back/edit/');
                            }
                        ";
	}

	public function getHeaderText()
	{
		$location = Mage::registry('locations_data');
		if ($location && $location->getId())
		{
			return Mage::helper('events')->__('Edit Location: "%s"', $this->escapeHtml($location->getLocation()));
		}
		else {
			return Mage::helper('events')->__('New Location');
		}
	}


}