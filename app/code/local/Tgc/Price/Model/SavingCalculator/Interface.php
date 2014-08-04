<?php
/**
 * Saving calculator interface
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
interface Tgc_Price_Model_SavingCalculator_Interface
{
    /**
     * Returns true if calculator supports product
     *
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     */
    public function canCalculate(Mage_Catalog_Model_Product $product);

    /**
     * Sets product for calculation
     *
     * @param Mage_Catalog_Model_Product $product
     */
    public function setProduct(Mage_Catalog_Model_Product $product);

    /**
     * Calculates savings of product
     *
     * @return array Savings by media format ordered by descent
     */
    public function calculate();
}