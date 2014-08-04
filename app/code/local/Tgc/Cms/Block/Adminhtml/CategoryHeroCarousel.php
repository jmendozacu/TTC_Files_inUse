<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Block_Adminhtml_CategoryHeroCarousel extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_categoryHeroCarousel';
        $this->_blockGroup = 'tgc_cms';
        $this->_headerText = Mage::helper('tgc_cms')->__('Category Hero Carousel Manager');
        $this->_addButtonLabel = Mage::helper('tgc_cms')->__('Add Carousel Item');

        parent::__construct();
    }
}