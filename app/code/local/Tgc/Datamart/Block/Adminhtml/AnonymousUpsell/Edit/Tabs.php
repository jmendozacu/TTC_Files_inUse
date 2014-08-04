<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Block_Adminhtml_AnonymousUpsell_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('anonymousUpsell_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('tgc_datamart')->__('Anonymous Upsells'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('tgc_datamart')->__('Anonymous Upsells'),
            'title'     => Mage::helper('tgc_datamart')->__('Anonymous Upsells'),
            'content'   => $this->getLayout()->createBlock('tgc_datamart/adminhtml_anonymousUpsell_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}
