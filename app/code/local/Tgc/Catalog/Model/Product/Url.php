<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Catalog_Model_Product_Url extends Enterprise_Catalog_Model_Product_Url
{
    const XML_PATH_COURSE_URL_PREFIX = 'catalog/seo/product_url_prefix';
    const PREFIX_SET = 'sets';

    private $_coursePrefix = array();


    /**
     * Returns possible prefixes of product URL
     *
     * @param string $storeId
     * @return array<string>
     */
    public function getPrefixes($storeId = null)
    {
        return array($this->_getCoursePrefix(), self::PREFIX_SET);
    }

    protected function _getCoursePrefix($storeId = null)
    {
        if ($storeId === null) {
            $storeId = Mage::app()->getStore()->getId();
        }
        if (!isset($this->_coursePrefix[$storeId])) {
            $this->_coursePrefix[$storeId] = Mage::getStoreConfig(self::XML_PATH_COURSE_URL_PREFIX, $storeId);
        }

        return $this->_coursePrefix[$storeId];
    }

    protected function _getProductUrl($product, $requestPath, $routeParams)
    {
        $categoryId = $this->_getCategoryIdForUrl($product, $routeParams);
        $storeId = $this->getUrlInstance()->getStore()->getId();

        if (!empty($requestPath)) {
            if ($categoryId) {
                $category = $this->_factory->getModel('catalog/category', array('disable_flat' => true))
                    ->load($categoryId);
                if ($category->getId()) {
                    $categoryRewrite = $this->_factory->getModel('enterprise_catalog/category')
                        ->loadByCategory($category);
                    if ($categoryRewrite->getId()) {
                        $requestPath = $categoryRewrite->getRequestPath() . '/' . $requestPath;
                    }
                }
            }

            $requestPath = $this->_addPrefixToUrl($requestPath, $this->_getPrefixByProduct($product, $storeId));
            $product->setRequestPath($requestPath);
            $requestPath = $this->_factory->getHelper('enterprise_catalog')
                ->getProductRequestPath($requestPath, $storeId);

            return $this->getUrlInstance()->getDirectUrl($requestPath, $routeParams);
        }

        $requestPath = $this->_addPrefixToUrl($product->getUrlKey(), $this->_getPrefixByProduct($product, $storeId));
        $routeParams['id'] = $product->getId();
        $routeParams['s'] = $requestPath;
        if ($categoryId) {
            $routeParams['category'] = $categoryId;
        }
        return $this->getUrlInstance()->getUrl('catalog/product/view', $routeParams);
    }

    private function _getPrefixByProduct(Mage_Catalog_Model_Product $product, $storeId)
    {
        return $this->_factory->getHelper('tgc_catalog')->isSetProduct($product)
            ? self::PREFIX_SET
            : $this->_getCoursePrefix($storeId);
    }

    private function _addPrefixToUrl($url, $prefix)
    {
        return $prefix ? "$prefix/$url" : $url;
    }
}