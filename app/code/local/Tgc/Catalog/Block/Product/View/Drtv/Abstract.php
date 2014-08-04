<?php
/**
 * Tgc Catalog
 *
 * @author      Guidance Magento SuperTeam <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 *
 */
class Tgc_Catalog_Block_Product_View_Drtv_Abstract extends Mage_Cms_Block_Block
{
    const AD_TYPE_MOBILE = 'header_cms_mobile';

    const AD_TYPE_DESKTOP = 'header_cms_desktop';

    protected $_adCodeHelper;

    protected function _construct()
    {
        parent::_construct();
        $this->_adCodeHelper = Mage::helper('adcoderouter');
        $this->setIdOfStaticBlock();
    }

    public function setIdOfStaticBlock()
    {
        //if admin has not specified block type, then no block is given.
        if($this->_adCodeHelper->isDrtvAd()) {
            if($blockIdentifier = $this->_adCodeHelper->getAdCodeRedirectValue($this->_adTypeMedia)) {
                $this->setBlockId($blockIdentifier);
            }
        }
    }
}