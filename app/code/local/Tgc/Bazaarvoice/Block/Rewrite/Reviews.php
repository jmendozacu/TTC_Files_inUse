<?php

include Mage::getModuleDir('files', 'Tgc_Bazaarvoice') . DS . 'files' . DS . 'bvseosdk.php';

class Tgc_Bazaarvoice_Block_Rewrite_Reviews extends Mage_Core_Block_Template
{
    const XML_PATH_CACHE_LIFETIME = 'bazaarvoice/cache_lifetime';

    private $_isEnabled;

    public function _construct()
    {
        // enabled/disabled in admin
        $this->_isEnabled = (bool)Mage::getStoreConfig('bazaarvoice/general/enable_bv');
    }

    /**
     * returns true if feature is enabled in admin, otherwise returns false
     * @return bool
     */
    public function getIsEnabled()
    {
        return $this->_isEnabled;
    }
    
    public function getSEOContent()
    {
        $seoContent = '';
        if(Mage::getStoreConfig('bazaarvoice/general/enable_bv')) {
            // Check if admin has configured a legacy display code
            if(strlen(Mage::getStoreConfig('bazaarvoice/general/display_code'))) {
                $deploymentZoneId =
                    Mage::getStoreConfig('bazaarvoice/general/display_code') .
                    '-' . Mage::getStoreConfig('bazaarvoice/general/locale');
            }
            else {
                $deploymentZoneId =
                    str_replace(' ', '_', Mage::getStoreConfig('bazaarvoice/general/deployment_zone')) .
                    '/' . Mage::getStoreConfig('bazaarvoice/general/locale');
            }
            try {
                $bv = new BV(array(
                    'deployment_zone_id' => $deploymentZoneId, // replace with your display code (BV provided)
                    'product_id' => Mage::helper('bazaarvoice')->getProductId(Mage::registry('current_product')), // replace with product id
                    'cloud_key' => Mage::getStoreConfig('bazaarvoice/general/cloud_seo_key'), // BV provided value
                    'staging' => TRUE
                ));
                $seoContent = $bv->reviews->renderSeo();
            } catch (Exception $e) {
                Mage::logException($e);
                $seoContent = 'Bazaarvoice throws exception';
            }
        }
        
        return $seoContent;
    }

    /**
     * Returns current product
     *
     * @throws DomainException
     * @return Mage_Catalog_Model_Product
     */
    private function _getCurrentProduct()
    {
        $product = Mage::registry('current_product');

        if (!$product instanceof Mage_Catalog_Model_Product) {
            throw new DomainException('Cannot load current product.');
        }

        return $product;
    }

    /**
     * Retrieve block cache tags based on category
     *
     * @return array
     */
    public function getCacheTags()
    {
        return array_merge(parent::getCacheTags(), array($this->_getCurrentProduct()->getSku()));
    }

    public function getCacheKeyInfo()
    {
        $info = parent::getCacheKeyInfo();
        $info[] = $this->_getCurrentProduct()->getSku();

        return $info;
    }

    public function getCacheLifetime()
    {
        return Mage::getStoreConfig(self::XML_PATH_CACHE_LIFETIME);
    }
}
