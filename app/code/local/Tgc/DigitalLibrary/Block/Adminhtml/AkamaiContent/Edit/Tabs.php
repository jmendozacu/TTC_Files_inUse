<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_Adminhtml_AkamaiContent_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('akamaiContent_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('tgc_dl')->__('Akamai Content'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('tgc_dl')->__('Akamai Content'),
            'title'     => Mage::helper('tgc_dl')->__('Akamai Content'),
            'content'   => $this->getLayout()->createBlock('tgc_dl/adminhtml_akamaiContent_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}
