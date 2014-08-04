<?php

class Tgc_Adcoderouter_Block_Adminhtml_Adcoderedirectlist extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = "adcoderouter";
        $this->_controller = "adminhtml_adcoderedirectlist";
        $this->_headerText = "Ad Code Redirects List";

        parent::__construct();

    }
}