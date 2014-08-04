<?php
/**
 * User: mhidalgo
 * Date: 11/03/14
 * Time: 16:53
 */
class Tgc_Zmag_Block_Adminhtml_Form_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('tgc_zmag_tabs');
        $this->setDestElementId('edit_form'); // this should be same as the form id define above
        $this->setTitle(Mage::helper('tgc_zmag')->__('Zmag Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('tgc_zmag')->__('Zmag Information'),
            'title'     => Mage::helper('tgc_zmag')->__('Zmag Information'),
            'content'   => $this->getLayout()->createBlock('tgc_zmag/adminhtml_form_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}