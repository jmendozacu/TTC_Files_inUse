<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Product type price model
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Ayasoftware_SimpleProductPricing_Catalog_Model_Product_Type_Configurable_Price extends Mage_Catalog_Model_Product_Type_Price
{

	/**
	 * Check if string s1 contains string s2
	 * @param $s1
	 * @param $s2
	 * @return boolean: true is yes
	 */
	protected function strContains ($s1, $s2)
	{
		$pos = strpos(strtolower($s1), strtolower($s2));
		if ($pos !== false) {
			return true;
		}
		return false;
	}

    /**
     * Check for cart_migrate cookie to see if this is the first visit to the site since their last visit on the old site
     */
    protected function _isCartMigrated()
    {
        return Mage::getModel('core/cookie')->get(Tgc_Checkout_Model_Cart_Migrate::CART_MIGRATE_COOKIE_NAME);  // Will return 1 if exists, null if not
    }
    /**
	 * Get product final price
	 *
	 * @param   double $qty
	 * @param   Mage_Catalog_Model_Product $product
	 * @return  double
	 */
	public function getFinalPrice ($qty = null, $product)
	{
		$session = Mage::getSingleton('checkout/session');
		$currentUrl = Mage::helper('core/url')->getCurrentUrl();
		$tierPrice = 0;
		$simplePrice = 0;
        $cartMigrated = $this->_isCartMigrated();

		if (!$cartMigrated OR ($this->strContains($currentUrl, "aitcheckout")
            || $this->strContains($currentUrl, "onestepcheckout")
	        || $this->strContains($currentUrl, "checkout/cart")
	        || $this->strContains($currentUrl, "checkout/onepage")
	        || $this->strContains($currentUrl, "paypal/express")
	        || $this->strContains($currentUrl, "checkout/multishipping")
	        || $this->strContains($currentUrl, "authorizenet/directpost_payment")
	        || $this->strContains($currentUrl, "firecheckout")
	        || $this->strContains($currentUrl, "customer/account/loginPost")
            || $this->strContains($currentUrl, "tgc_wishlist/index/cart")
	        || $this->strContains($currentUrl, "customer/account/headerLogin")
            || $this->strContains($currentUrl, "giftcard/cart"))) {

			if (is_null($qty) && ! is_null($product->getCalculatedFinalPrice())) {
				return $product->getCalculatedFinalPrice();
			}
			$catalog = Mage::getModel('catalog/product'); // for some reason i will need to reload product to get attributes
			$_config = $catalog->load($product->getId());
			if ($_config->getUseStandardConfig()) {
				$simplePrice =  parent::getFinalPrice($qty, $product);
			}
			$productOptions = $product->getTypeInstance(true)->getOrderOptions($product);
            if (empty($productOptions['simple_sku'])) {
                Mage::log('Ayasoftware_SimpleProductPricing_Catalog_Model_Product_Type_Configurable_Price::getFinalPrice simple_sku is empty. Parent product: ' . $product->getSku() . ' Current url: ' . $currentUrl);
            }
            if (isset($productOptions['simple_sku'])) {
                $simple = $catalog->load($product->getIdBySku($productOptions['simple_sku']));
                $simplePrice = $simple->getFinalPrice();
                if ($simple->getCustomerGroupId()) {
                    $simplePrice = $simple->getGroupPrice();
                }
                if ($this->canApplyTierPrice($simple, $qty)) {
                    $simplePrice = $simple->getTierPrice($qty);
                }

                if ($simple->special_price) {
                    $simplePrice = min($simple->getFinalPrice(), $simplePrice);
                }
            }

		/*
			// BOF super attributes configuration
		$finalPrice = parent::getFinalPrice($qty, $product);
		$beforeSelections =  parent::getFinalPrice($qty, $product);
		$product->getTypeInstance(true)->setStoreFilter($product->getStore(), $product);
		$attributes = $product->getTypeInstance(true)->getConfigurableAttributes($product);
		$selectedAttributes = array();
		if ($product->getCustomOption('attributes')) {
			$selectedAttributes = unserialize($product->getCustomOption('attributes')->getValue());
		}
		$basePrice = $simplePrice;
		foreach ($attributes as $attribute) {
			$attributeId = $attribute->getProductAttribute()->getId();
			$value = $this->_getValueByIndex($attribute->getPrices() ? $attribute->getPrices() : array(), isset($selectedAttributes[$attributeId]) ? $selectedAttributes[$attributeId] : null);
			if ($value) {
				if ($value['pricing_value'] != 0) {
					$finalPrice += $this->_calcSelectionPrice($value, $basePrice);
				}
			}
		}
		$super_attributes_price =  $finalPrice - $beforeSelections;
		// EOF super attributes configuration

		 */
            if (isset($simple) && $this->applyRulesToProduct($simple)) {
                $rulePrice = $this->applyRulesToProduct($simple);
                if ($this->applyOptionsPrice($product, $simplePrice)) {
                    $rulePrice = $this->applyOptionsPrice($product, $rulePrice);
                    $simplePrice = $this->applyOptionsPrice($product, $simplePrice);
                }
                $product->setFinalPrice(min($simplePrice, $rulePrice));
                return min($simplePrice, $rulePrice);
            } else {
                if ($this->applyOptionsPrice($product, $simplePrice)) {
                    $simplePrice = $this->applyOptionsPrice($product, $simplePrice);
                }
                $product->setFinalPrice($simplePrice);
                return $simplePrice;
            }
		} else {
			return $this->getDefaultPrice($qty = null, $product);
		}
	}
	// Use Magento Default getFinalPrice()
	public function getDefaultPrice ($qty = null, $product){

		if (is_null($qty) && ! is_null($product->getCalculatedFinalPrice())) {
			return $product->getCalculatedFinalPrice();
		}
		$finalPrice = parent::getFinalPrice($qty, $product);
		$product->getTypeInstance(true)->setStoreFilter($product->getStore(), $product);
		$attributes = $product->getTypeInstance(true)->getConfigurableAttributes($product);
		$selectedAttributes = array();
		if ($product->getCustomOption('attributes')) {
			$selectedAttributes = unserialize($product->getCustomOption('attributes')->getValue());
		}
		$basePrice = $finalPrice;
		foreach ($attributes as $attribute) {
			$attributeId = $attribute->getProductAttribute()->getId();
			$value = $this->_getValueByIndex($attribute->getPrices() ? $attribute->getPrices() : array(), isset($selectedAttributes[$attributeId]) ? $selectedAttributes[$attributeId] : null);
			if ($value) {
				if ($value['pricing_value'] != 0) {
					$finalPrice += $this->_calcSelectionPrice($value, $basePrice);
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

	protected function _calcSelectionPrice ($priceInfo, $productPrice)
	{
		if ($priceInfo['is_percent']) {
			$ratio = $priceInfo['pricing_value'] / 100;
			$price = $productPrice * $ratio;
		} else {
			$price = $priceInfo['pricing_value'];
		}
		return $price;
	}
	protected function _getValueByIndex ($values, $index)
	{
		foreach ($values as $value) {
			if ($value['value_index'] == $index) {
				return $value;
			}
		}
		return false;
	}

	protected function applyOptionsPrice($product, $finalPrice)
	{
		if ($optionIds = $product->getCustomOption('option_ids')) {
			$basePrice = $finalPrice;
			foreach (explode(',', $optionIds->getValue()) as $optionId) {
				if ($option = $product->getOptionById($optionId)) {

					$confItemOption = $product->getCustomOption('option_'.$option->getId());
					$group = $option->groupFactory($option->getType())
					->setOption($option)
					->setConfigurationItemOption($confItemOption);

					$finalPrice += $group->getOptionPrice($confItemOption->getValue(), $basePrice);
				}
			}
		}
		return $finalPrice;
	}
	protected function canApplyTierPrice($product, $qty){
		$tierPrice  = $product->getTierPrice($qty);
		$price = $product->getPrice();
		if ($tierPrice != $price ){
			return true;
		} else {
			return false;
		}
	}
	/**
	 *
	 * Apply Catalog Rules...
	 * @param  int|Mage_Catalog_Model_Product $product
	 */

	public function applyRulesToProduct($product)
	{
		$rule = Mage::getModel("catalogrule/rule");
		return $rule->calcProductPriceRule($product,$product->getPrice());
	}

 //Force tier pricing to be empty for configurable products:
    public function getTierPrice($qty=null, $product)
    {
        return array();
    }

    public function getMaxPossibleFinalPrice($product) {
    	$maxPrice = $this-> getChildProductPrices ($product);
    	return $maxPrice['Max'];
    }
public function getChildProductPrices ($product){
		static $childrenPricesCache = array();
		$cacheKey = $product->getId();
		if (isset($childrenPricesCache[$cacheKey])) {
			return $childrenPricesCache[$cacheKey];
		}
		if($product->getTypeId() != 'configurable'){
			return;
		}

		$childProducts = $product->getTypeInstance(true)->getUsedProductCollection($product);
		$childProducts->addAttributeToSelect(array('msrp' , 'price' , 'special_price' , 'status' , 'special_from_date' , 'special_to_date'));
	    foreach ($childProducts as $childProduct) {
				if (!$childProduct->isSalable()) {
					continue;
				}
			    $salableChildProductPrices[] =  $childProduct->getFinalPrice();
		}
		$childProductPrices = $salableChildProductPrices;
		$childrenPricesCache[$cacheKey] = $childProductPrices;
		return array("Max" => max($childProductPrices) , "Min" => min($childProductPrices) );

	}

}