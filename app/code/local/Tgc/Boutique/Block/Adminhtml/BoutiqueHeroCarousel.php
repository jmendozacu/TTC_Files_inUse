<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @boutique    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Block_Adminhtml_BoutiqueHeroCarousel extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_boutiqueHeroCarousel';
        $this->_blockGroup = 'tgc_boutique';
        $this->_headerText = Mage::helper('tgc_boutique')->__('Boutique Hero Carousel Manager');
        $this->_addButtonLabel = Mage::helper('tgc_boutique')->__('Add Carousel Item');

        parent::__construct();
    }
}
