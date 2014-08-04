<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @boutique    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Block_Adminhtml_BoutiqueHeroCarousel_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('boutiqueHeroCarousel_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('tgc_boutique')->__('Boutique Hero Carousel Items'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('tgc_boutique')->__('Carousel Items'),
            'title'     => Mage::helper('tgc_boutique')->__('Carousel Items'),
            'content'   => $this->getLayout()->createBlock('tgc_boutique/adminhtml_boutiqueHeroCarousel_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}
