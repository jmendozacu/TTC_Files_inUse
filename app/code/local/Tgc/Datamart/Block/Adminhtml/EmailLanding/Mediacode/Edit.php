<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Block_Adminhtml_EmailLanding_Mediacode_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'entity_id';
        $this->_blockGroup = 'tgc_datamart';
        $this->_controller = 'adminhtml_emailLanding_mediacode';

        parent::__construct();

        $this->_addButton('save_and_edit', array(
            'label'     => Mage::helper('adminhtml')->__('Save and Continue Edit'),
            'class'     => 'save',
            'onclick'   => 'editForm.submit(\'' . $this->getSaveAndContinueUrl() . '\');'
        ), 100);

        if(!Mage::registry('tgc_datamart_landing_media_code')->getId()) {
            $this->_removeButton('delete');
        }
    }

    /**
     * Return save and continue url for edit form
     *
     * @return string
     */
    public function getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', array('_current' => true, 'back' => 'edit'));
    }

    public function getHeaderText()
    {
        if(!is_null(Mage::registry('tgc_datamart_landing_media_code')->getId())) {
            return $this->__('Edit Landing Page Media Code "%s"', $this->escapeHtml(Mage::registry('tgc_datamart_landing_media_code')->getMediaCode()));
        } else {
            return $this->__('New Landing Page Media Code');
        }
    }
}
