<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Block_EmailLanding_Banner_Set extends Mage_Core_Block_Template
{
    /**
     * Check that block can be shown
     *
     * @return bool
     */
    public function canShow()
    {
        return $this->getProduct() && $this->getProduct()->getId();
    }

    /**
     * Retrieve product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        if (!$this->hasData('product')) {
            $banner = $this->getBanner();
            $this->setProduct(false);
            if ($banner && $banner->getSetSku()) {
                $product = Mage::getModel('catalog/product');
                $product->load($product->getIdBySku($banner->getSetSku()));
                if (Mage::helper('catalog/product')->canShow($product) && $product->isSaleable()) {
                    $this->setProduct($product);
                }
            }
        }

        return $this->getData('product');
    }

    /**
     * Retrive banner model
     *
     * @return Tgc_Datamart_Model_EmailLanding_Banner
     */
    public function getBanner()
    {
        if (!$this->hasData('banner')) {
            $this->setBanner($this->getParentBlock()->getBanner());
        }

        return $this->getData('banner');
    }

    /**
     * Retrieve Set Text
     *
     * @return string
     */
    public function getText()
    {
        return $this->getBanner() ? $this->getBanner()->getSetText() : '';
    }
}
