<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Catalog_Model_Category_Url extends Enterprise_Catalog_Model_Category_Url
{
    const XML_PATH_CATEGORY_URL_PREFIX = 'catalog/seo/category_url_prefix';

    private $_prefix;

    public function getPrefixes($storeId = null)
    {
        return array($this->_getPrefix($storeId));
    }

    /**
     * Get direct URL to category
     *
     * @param Mage_Catalog_Model_Category $category
     * @return string
     */
    protected function _getDirectUrl(Mage_Catalog_Model_Category $category)
    {
        /** @var $helper Enterprise_Catalog_Helper_Data */
        $helper      = $this->_factory->getHelper('enterprise_catalog');
        $requestPath = $helper->getCategoryRequestPath($category->getRequestPath(), $category->getStoreId());
        $requestPath = $this->_addPrefixToPath($requestPath, $this->_getPrefix(), $category->getStoreId());

        return $this->getUrlInstance()->getDirectUrl($requestPath);
    }

    protected function _getPrefix($storeId = null)
    {
        if ($storeId === null) {
            $storeId = Mage::app()->getStore()->getId();
        }
        if (!isset($this->_prefix[$storeId])) {
            $this->_prefix[$storeId] = Mage::getStoreConfig(self::XML_PATH_CATEGORY_URL_PREFIX, $storeId);
        }

        return $this->_prefix[$storeId];
    }

    private function _addPrefixToPath($path, $prefix)
    {
        return $prefix ? "$prefix/$path" : $path;
    }
}