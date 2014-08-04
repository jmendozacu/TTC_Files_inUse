<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_MobileApi_Model_Course_Abstract extends Tgc_MobileApi_Model_Resource
{
    const MEDIA_TYPE_AUDIO = 'audio';
    const MEDIA_TYPE_VIDEO = 'video';

    const DATE_FORMAT = 'n/d/Y'; // '1/15/2014

    private $_categoryNameCache = array();

    protected function _getCategoryName(Mage_Catalog_Model_Product $product)
    {
        $categoryIds = $product->getCategoryIds();
        $categoryId = reset($categoryIds);

        if (!$categoryId) {
            return null;
        }
        if (isset($this->_categoryNameCache[$categoryId])) {
            return $this->_categoryNameCache[$categoryId];
        }
        $category = Mage::getModel('catalog/category')->load($categoryId);

        return $this->_categoryNameCache[$categoryId] = $category->getName();
    }

    protected function _getMediaType($mediaFormat)
    {
        if ($mediaFormat === null) {
            return null;
        } else if ($mediaFormat == Tgc_DigitalLibrary_Model_Source_Format::AUDIO) {
            return self::MEDIA_TYPE_AUDIO;
        } else if ($mediaFormat == Tgc_DigitalLibrary_Model_Source_Format::VIDEO) {
            return self::MEDIA_TYPE_VIDEO;
        }
    }

    protected function _getCourseId(Mage_Catalog_Model_Product $product)
    {
        $id = $product->getCourseId();

        switch ($product->getFormat()) {
            case Tgc_DigitalLibrary_Model_Source_Format::AUDIO:
                return $id . 'A';
            case Tgc_DigitalLibrary_Model_Source_Format::VIDEO:
                return $id . 'V';
            default:
                return $id;
        }
    }
}