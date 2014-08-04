<?php

/**
 * Class Tgc_Ultimo_Helper_Labels
 *
 * Changes the algorithm of getting onSale flag data.
 *
 */
class Tgc_Ultimo_Helper_Labels extends Infortis_Ultimo_Helper_Labels
{
    /**
     * Check if "sale" label is enabled and if product has special price
     *
     * @param Mage_Catalog_Model_Product $product
     * @return  bool
     */
	public function isOnSale($product)
	{
		return $product->getOnSaleFlag();
	}
}
