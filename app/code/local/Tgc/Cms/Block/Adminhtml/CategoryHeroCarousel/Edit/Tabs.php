<?php
/**
 * User: mhidalgo
 * Date: 14/03/14
 * Time: 09:39
 */
class Tgc_Cms_Block_Adminhtml_CategoryHeroCarousel_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('categoryHeroCarousel_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('tgc_cms')->__('Category Hero Carousel Items'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('tgc_cms')->__('Carousel Items'),
            'title'     => Mage::helper('tgc_cms')->__('Carousel Items'),
            'content'   => $this->getLayout()->createBlock('tgc_cms/adminhtml_categoryHeroCarousel_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}