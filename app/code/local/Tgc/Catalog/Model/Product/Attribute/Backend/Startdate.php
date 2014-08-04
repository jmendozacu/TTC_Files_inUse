<?php
/**
 * Tgc Catalog
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Catalog_Model_Product_Attribute_Backend_Startdate extends Mage_Catalog_Model_Product_Attribute_Backend_Startdate
{
    /**
     * Get attribute value for save.
     *
     * @param Varien_Object $object
     * @return string|bool
     */
    protected function _getValueForSave($object)
    {
        $attributeName  = $this->getAttribute()->getName();
        $startDate      = $object->getData($attributeName);
        if ($startDate === false) {
            return false;
        }
        if ($startDate == '' && $object->getSpecialPrice() && $object->getSpecialPrice() != '0.00') {
            $startDate = Mage::app()->getLocale()->date();
        }

        return $startDate;
    }
}