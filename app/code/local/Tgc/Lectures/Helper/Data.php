<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Lectures_Helper_Data extends Mage_Core_Helper_Abstract
{
    const GUIDEBOOK_URL_SUFFIX = 'media/catalog/guidebook/';

    public function getGuidebooksUrl($filename)
    {
        $urlOriginal = Mage::getUrl() . self::GUIDEBOOK_URL_SUFFIX . $filename;
        $url = str_replace('index.php/','',$urlOriginal);
        return $url;
    }

    /**
     * Returns lectures for product
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Tgc_Lectures_Model_Resource_Lectures_Collection
     */
    public function getLecturesForProduct(Mage_Catalog_Model_Product $product)
    {
        return Mage::getResourceModel('lectures/lectures_collection')
            ->addFieldToSelect('*')
            ->addProductToFilter($product)
            ->setOrder('lecture_number', Varien_Data_Collection::SORT_ORDER_ASC);
    }

    public function isCustomerFreeLectureProspect(Mage_Customer_Model_Customer $customer)
    {
        return $customer->getWebProspectId() ? true : false;
    }
}