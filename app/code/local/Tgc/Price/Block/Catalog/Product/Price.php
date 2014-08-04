<?php

/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Price
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Price_Block_Catalog_Product_Price extends Ayasoftware_SimpleProductPricing_Catalog_Block_Product_Price
{
    /**
     * Rewritten to accommodate configurable products which only have a single underlying simple product
     *
     * @return mixed|string
     */
    public function _toHtml()
    {
        if ($this->getTemplate() == 'catalog/product/price.phtml') {
            $product = $this->getProduct();
            $prices = $this->getChildProductPrices($product);
            if (is_object($product) && $product->getTypeId() == 'configurable') {
                /* @var $priceCalc Tgc_Price_Helper_Calc */
                $priceCalc = Mage::helper('tgc_price/calc');
                Mage::app()->getRequest()->getControllerName();
                $showFromPrice = Mage::getStoreConfig('spp/setting/showfromprice');
                $htmlToInsertbefore = '</div>';
                $sale = Mage::helper('ultimo/labels')->isOnSale($product) ? 'sale' : '';
                $start = '<div class="price-box '  . $sale . '">';
                $extraHtml = '<span class="price-range" id="configurable-price-to-' . $product->getId() . $this->getIdSuffix() . '">';

                if ($priceCalc->shouldShowMaxSaving($product)) {
                    $extraHtml .= '<span class="save-up price-range" id="price-range-' . $product->getId() . $this->getIdSuffix() . '"><span class="label">' . $this->__('Save up to %s', Mage::helper('core')->currency($priceCalc->getMaxSaving($product))) . '</span>';
                } else if ($showFromPrice) {
                    $extraHtml .= '<span class="price-range" id="price-range-' . $product->getId() . $this->getIdSuffix() . '"><span class="label">' . $this->__('Starting at %s', Mage::helper('core')->currency($prices['Min'])) . '</span>';
                }
                $extraHtml .= '</span></span>';
                return $start . $extraHtml . $htmlToInsertbefore;
            }
        }
        return parent::_toHtml();
    }
}