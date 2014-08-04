<?php
/**
 * User: mhidalgo
 * Date: 08/04/14
 * Time: 12:03
 */

class Tgc_Catalog_Model_Category extends Mage_Catalog_Model_Category {

    /**
     * Retrieve Product Listing Default Sort By
     *
     * @return string
     */
    public function getDefaultSortBy() {
        if (!$sortBy = $this->getData('default_sort_by')) {
            if (Mage::helper('tgc_customer')->isFreeLectureProspect()) {
                $sortBy = Mage::helper('tgc_customer')->getAttributeBestSellerByUserType();
            } else {
                $sortBy = Mage::getSingleton('catalog/config')
                    ->getProductListDefaultSortBy($this->getStoreId());
            }
        }
        $available = $this->getAvailableSortByOptions();
        if (!isset($available[$sortBy])) {
            $sortBy = array_keys($available);
            $sortBy = $sortBy[0];
        }
        return $sortBy;
    }
}