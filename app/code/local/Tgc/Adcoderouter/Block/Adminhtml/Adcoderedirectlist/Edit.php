<?php

class Tgc_Adcoderouter_Block_Adminhtml_Adcoderedirectlist_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'adcoderouter';
        $this->_controller = 'adminhtml_adcoderedirectlist';

        parent::__construct();

        $this->_updateButton('delete', 'label','Delete Redirect');
        $this->_updateButton('save','label','Save Redirect');
        $this->_removeButton('reset');
    }

    public function getHeaderText()
    {
        $headerText = "Ad Code Redirect Information";
        if($id = Mage::registry('adcoderouter_redirects')->getId()) {
            $headerText .= " ( ID: " . $id . " )";
        } else {
            return $this->__('New Ad Code Redirect');
        }

        return $headerText;
    }
}