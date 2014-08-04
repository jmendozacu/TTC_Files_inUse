<?php
class Tealium_Tags_Helper_Data extends Mage_Core_Helper_Abstract {

    public function isEnabled($store) {
        return Mage::getStoreConfig('tealium_tags/general/enable', $store);
    }

    public function getTealiumBaseUrl($store){
        $account = $this->getAccount($store);
        $profile = $this->getProfile($store);
        $env = $this->getEnv($store);
        return "//tags.tiqcdn.com/utag/$account/$profile/$env/utag.js";
    }

    public function getSimpleProducts($_product){
        $configurable = Mage::getModel('catalog/product_type_configurable')->setProduct($_product);
        $collection = $configurable->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();

        return $collection;
    }

    public function getSimpleSkus($_product){
        $_simples = $this->getSimpleProducts($_product);
        $_simple_skus = '';
        foreach($_simples as $_simple){
            $_simple_skus .= $_simple->getSku() . ',';
        }

        return substr($_simple_skus, 0, -1);
    }

    public function getSimpleIds($_product){
        $_simples = $this->getSimpleProducts($_product);
        $_simple_ids = '';
        foreach($_simples as $_simple){
            $_simple_ids .= $_simple->getCourseId() . ',';
        }

        return substr($_simple_ids, 0, -1);
    }

    public function getSimpleListPrices($_product){
        $_simples = $this->getSimpleProducts($_product);
        $_simple_list_prices = '';
        foreach($_simples as $_simple){
            $_simple_list_prices .= number_format($_simple->getPrice(), 2) . ',';
        }

        return substr($_simple_list_prices, 0, -1);
    }

    public function getSimpleDefaultPrices($_product){
        $_simples = $this->getSimpleProducts($_product);
        $_simple_default_prices = '';
        foreach($_simples as $_simple){
            $_simple_default_prices .= number_format($_simple->getSpecialPrice(), 2) . ',';
        }

        return substr($_simple_default_prices, 0, -1);
    }

    public function getSimplePriorityPrices($_product){
        $_simples = $this->getSimpleProducts($_product);
        $_simple_priority_prices = '';
        foreach($_simples as $_simple){
            $_simple_priority_prices .= number_format($_simple->getFinalPrice(), 2) . ',';
        }

        return substr($_simple_priority_prices, 0, -1);
    }

    public function getAccount($store) {
        return Mage::getStoreConfig('tealium_tags/general/account', $store);
    }

    public function getProfile($store) {
        return Mage::getStoreConfig('tealium_tags/general/profile', $store);
    }
    public function getEnv($store) {
        return Mage::getStoreConfig('tealium_tags/general/env', $store);
    }

}
