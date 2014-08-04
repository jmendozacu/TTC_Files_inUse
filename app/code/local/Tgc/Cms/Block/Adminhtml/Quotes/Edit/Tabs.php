<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Block_Adminhtml_Quotes_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('quotes_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('tgc_cms')->__('Quotes Items'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('tgc_cms')->__('Quotes Items'),
            'title'     => Mage::helper('tgc_cms')->__('Quotes Items'),
            'content'   => $this->getLayout()->createBlock('tgc_cms/adminhtml_quotes_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}
