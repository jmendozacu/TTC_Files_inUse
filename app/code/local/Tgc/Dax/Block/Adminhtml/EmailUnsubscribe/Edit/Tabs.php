<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Block_Adminhtml_EmailUnsubscribe_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('emailUnsubscribe_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('tgc_dax')->__('Email Unsubscribes'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('tgc_dax')->__('Email Unsubscribes'),
            'title'     => Mage::helper('tgc_dax')->__('Email Unsubscribes'),
            'content'   => $this->getLayout()->createBlock('tgc_dax/adminhtml_emailUnsubscribe_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}
