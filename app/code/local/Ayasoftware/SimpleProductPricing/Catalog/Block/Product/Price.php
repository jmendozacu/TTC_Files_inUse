<?php
class Ayasoftware_SimpleProductPricing_Catalog_Block_Product_Price  extends Mage_Catalog_Block_Product_Price
{
	public function _toHtml() {
		 if ( !Mage::getStoreConfig('spp/setting/showfromprice')) {
		 	return parent::_toHtml();
		 }
		$htmlToInsertAfter = '<div class="price-box">';
		if ($this->getTemplate() == 'catalog/product/price.phtml') {
			$product = $this->getProduct();
			$prices = $this->getChildProductPrices($product) ;
			if (is_object($product) && $product->getTypeId() == 'configurable') {
				Mage::app()->getRequest()->getControllerName();
				$maxPrice = $prices['Max'];
				$minPrice = $prices['Min'];
				if ($minPrice == $maxPrice) {
					return parent::_toHtml();
				}
				if(Mage::app()->getRequest()->getControllerName() != 'product'){
					$htmlToInsertbefore = '</div>';
                     $start = '<div class="price-box">';
					if ($minPrice != $maxPrice) {
						$extraHtml = '<span class="price-range" id="configurable-price-to-' . $product->getId() . $this->getIdSuffix() . '">';
						$extraHtml .= $this->__('<span class="price-range" id="price-range-' . $product->getId() . $this->getIdSuffix() . '">' . $this->__('From: ') . Mage::helper('core')->currency($minPrice));
						$extraHtml .= '</span></span>';
						return $start . $extraHtml . $htmlToInsertbefore;
					}
			    } else {
			    	$extraHtml = '<span class="label" id="configurable-price-from-'
					. $product->getId()
					. $this->getIdSuffix()
					. '"><span class="configurable-price-from-label">';
					if ($minPrice != $maxPrice) {
						$extraHtml .= $this->__('From: ');
					}
					$extraHtml .= '</span></span>';
                    $priceHtml = str_replace(Mage::helper('core')->currency($product->getFinalPrice(), true, false), Mage::helper('core')->currency($minPrice, true, false), parent::_toHtml());
					#manually insert extra html needed by the extension into the normal price html
					return substr_replace($priceHtml, $extraHtml, strpos($priceHtml, $htmlToInsertAfter)+strlen($htmlToInsertAfter),0);
				}
			}
		}
		return parent::_toHtml();
	}


	public function getChildProductPrices ($product){
		static $childrenPricesCache = array();
		$childProductPrices = array();
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
		    if ($childProduct->isSalable()) {
			if($childProduct->getTierPrice()) {
		           $tprices = array();
			   foreach ($tierprices = $childProduct->getTierPrice() as $tierprice) {
				  $tprices[] = $tierprice['price'];
			   }
			}
			if(!empty($tprices)){
			   $tierpricing = min($tprices);
		           $childProductPrices[] =  $tierpricing;
			}
		        $childProductPrices[] = $childProduct->getFinalPrice();
	            }
		}
		$childrenPricesCache[$cacheKey] = empty($childProductPrices)
		    ? array("Max" => 0, "Min" => 0)
		    : array("Max" => max($childProductPrices), "Min" => min($childProductPrices));

		return $childrenPricesCache[$cacheKey];
	}

}