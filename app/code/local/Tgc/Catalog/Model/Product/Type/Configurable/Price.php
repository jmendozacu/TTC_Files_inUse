<?php
/**
 * User: mhidalgo
 * Date: 09/04/14
 * Time: 16:57
 */

class Tgc_Catalog_Model_Product_Type_Configurable_Price extends Ayasoftware_SimpleProductPricing_Catalog_Model_Product_Type_Configurable_Price {
    // Use Magento Default getFinalPrice()
    public function getDefaultPrice ($qty = null, $product){

        if (is_null($qty) && ! is_null($product->getCalculatedFinalPrice())) {
            return $product->getCalculatedFinalPrice();
        }
        $mageModelPrice = Mage::helper('Guidance_Reflection')->getGrandParentClassName(get_class());
        $finalPrice = $mageModelPrice::getFinalPrice($qty, $product);
        $product->getTypeInstance(true)->setStoreFilter($product->getStore(), $product);
        $attributes = $product->getTypeInstance(true)->getConfigurableAttributes($product);
        $selectedAttributes = array();
        if ($product->getCustomOption('attributes')) {
            $selectedAttributes = unserialize($product->getCustomOption('attributes')->getValue());
        }
        $basePrice = $finalPrice;
        foreach ($attributes as $attribute) {
            if (is_null($attribute->getProductAttribute())) {
                Mage::log("Product to Fix ID='".$product->getId()."' Name='".$product->getName()."'",Zend_Log::CRIT,"Tgc_Catalog_Model_Product_Type_Configurable_Price.log");
            }else {
                $attributeId = $attribute->getProductAttribute()->getId();
                $value = $this->_getValueByIndex($attribute->getPrices() ? $attribute->getPrices() : array(), isset($selectedAttributes[$attributeId]) ? $selectedAttributes[$attributeId] : null);
                if ($value) {
                    if ($value['pricing_value'] != 0) {
                        $finalPrice += $this->_calcSelectionPrice($value, $basePrice);
                    }
                }
            }
        }

        if(Mage::app()->getRequest()->getControllerName() == 'product'){
            $prices = $this->getChildProductPrices($product) ;
            //if(isset($prices['Min']) && ($prices['Min'] < $finalPrice ) ) {
            if(isset($prices['Min']) ) {
                $product->setFinalPrice($prices['Min']);
                return max(0, $prices['Min']);
            } else {
                $product->setFinalPrice($finalPrice);
                return max(0, $product->getData('final_price'));
            }
        } else {
            $product->setFinalPrice($finalPrice);
            return max(0, $product->getData('final_price'));
        }
    }
}