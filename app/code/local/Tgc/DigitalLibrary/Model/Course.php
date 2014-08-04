<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Model_Course extends Mage_Catalog_Model_Product
{
    /**
     * Returns lectures of the course
     *
     * @return Tgc_Lectures_Model_Resource_Lectures_Collection
     */
    public function getLectures()
    {
        return Mage::getResourceModel('lectures/lectures_collection')
            ->addFieldToSelect('*')
            ->addProductToFilter($this);
    }
}