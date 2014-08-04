<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Block_Adminhtml_HeroCarousel_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('heroCarousel_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('tgc_cms')->__('Hero Carousel Items'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('tgc_cms')->__('Carousel Items'),
            'title'     => Mage::helper('tgc_cms')->__('Carousel Items'),
            'content'   => $this->getLayout()->createBlock('tgc_cms/adminhtml_heroCarousel_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}
