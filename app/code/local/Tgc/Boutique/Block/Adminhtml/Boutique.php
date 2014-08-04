<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Block_Adminhtml_Boutique extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_boutique';
        $this->_blockGroup = 'tgc_boutique';
        $this->_headerText = Mage::helper('tgc_boutique')->__('Boutique Manager');
        $this->_addButtonLabel = Mage::helper('tgc_boutique')->__('Add Boutique');

        parent::__construct();
    }
}
