<?php
/**
 * User: mhidalgo
 * Date: 11/03/14
 * Time: 14:59
 */
class Tgc_Zmag_Block_Adminhtml_Grid_Container extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        //where is the controller
        $this->_controller = 'adminhtml';
        $this->_blockGroup = 'tgc_zmag';
        //text in the admin header
        $this->_headerText = 'Zmag Administrator';
        //value of the add button
        parent::__construct();
    }
}