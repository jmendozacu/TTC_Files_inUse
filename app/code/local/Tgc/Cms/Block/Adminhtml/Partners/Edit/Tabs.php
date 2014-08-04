<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Block_Adminhtml_Partners_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('partners_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('tgc_cms')->__('Partners Items'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('tgc_cms')->__('Partners Items'),
            'title'     => Mage::helper('tgc_cms')->__('Partners Items'),
            'content'   => $this->getLayout()->createBlock('tgc_cms/adminhtml_partners_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}
