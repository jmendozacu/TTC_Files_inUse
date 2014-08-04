<?php
/**
 *
 */

class Tgc_Catalog_Block_Product_View extends Mage_Catalog_Block_Product_View
{

    protected function _construct()
    {
        $this->assign('adcodeHelper', Mage::helper('adcoderouter'));
        parent::_construct();
    }

    /**
     * Retrieve block cache tags
     *
     * @return array
     */
    public function getCacheTags()
    {
        return array_merge(parent::getCacheTags(), array('authenticated' => intval(Mage::helper('tgc_bv')->isAuthenticated())));
    }

    public function isSpaceAd()
    {
        return $this->_adCodeHelper()->isSpaceAd();
    }

    public function _adCodeHelper()
    {
        return Mage::helper('adcoderouter');
    }
}
