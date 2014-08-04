<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Block_Adminhtml_BoutiquePages_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('boutiquePages_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('tgc_boutique')->__('Boutique Pages Items'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('tgc_boutique')->__('Boutique Pages'),
            'title'     => Mage::helper('tgc_boutique')->__('Boutique Pages'),
            'content'   => $this->getLayout()->createBlock('tgc_boutique/adminhtml_boutiquePages_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}
