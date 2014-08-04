<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Price_Helper_Calc
{
    const XML_PATH_MAX_SAVING_THRESHOLD = 'tgc_price/max_saving_threshold';

    private $_savingsCache;

    /**
     * Return savings of set by format for current website and customer group
     * Caches resultsmiby
     *
     * @param Mage_Catalog_Model_Product $product Set product
     * @return array<media_format=>saving>|null Array of savings or null if product is not set
     */
    public function getSaving(Mage_Catalog_Model_Product $product)
    {
        $key = $product->getId();
        if (!isset($this->_savingsCache[$key])) {
            $this->_savingsCache[$key] = $this->_calculateSavingOfSet($product);
        }

        return $this->_savingsCache[$key];
    }

    /**
     * Calculates savings of set by format for current website and customer group
     *
     * @param Mage_Catalog_Model_Product $product Set product
     * @return array<media_format=>saving>|null Array of savings or null if product is not set
     */
    protected function _calculateSavingOfSet(Mage_Catalog_Model_Product $product)
    {
        try {
            return Mage::getSingleton('tgc_price/savingCalculator')->factory($product)->calculate();
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }

    /**
     * Returns max saving of set
     *
     * @param Mage_Catalog_Model_Product $product Setp product
     * @return float|null Saving or null if product is not set
     */
    public function getMaxSaving(Mage_Catalog_Model_Product $product)
    {
        $savings = $this->getSaving($product);

        return is_array($savings) ? reset($savings) : null; // savings ordered by desc in query
    }

    /**
     * Returns true if shuold show max saving
     *
     * @param Mage_Catalog_Model_Product $product
     * @return boolean
     */
    public function shouldShowMaxSaving(Mage_Catalog_Model_Product $product)
    {
        $onSale   = Mage::helper('ultimo/labels')->isOnSale($product);
        $isCourse = Mage::helper('tgc_catalog')->isCourseProduct($product);

        if (!$onSale) {
            return false;
        }
        $saving    = $this->getMaxSaving($product);
        $threshold = Mage::getStoreConfig(self::XML_PATH_MAX_SAVING_THRESHOLD);

        return (null !== $saving) && ($saving > $threshold);
    }
}
