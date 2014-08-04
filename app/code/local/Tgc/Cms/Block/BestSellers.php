<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Block_BestSellers extends Mage_Catalog_Block_Product_Widget_New
implements Mage_Widget_Block_Interface
{
    const CACHE_TAG               = 'BESTSELLERS_WIDGET';
    const GUEST_ATTRIBUTE         = 'guest_bestsellers';
    const AUTHENTICATED_ATTRIBUTE = 'authenticated_bestsellers';

    private $_collection;

    /**
     * Initialize block's cache and template settings
     */
    protected function _construct()
    {
        parent::_construct();

        if (empty($this->_template)) {
            $this->setTemplate('cms/widget/bestSellers.phtml');
        }

        if (!$this->getTitle()) {
            $this->setTitle('Best Sellers');
        }

        $cacheLifetime = $this->getCacheLifetime() ? $this->getCacheLifetime() : false;
        $this->addData(array('cache_lifetime' => $cacheLifetime));
        $this->addCacheTag(array(
            self::CACHE_TAG,
        ));

        $this->setIsResponsive(0);
    }

    /**
     * Get Key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return array(
            'CATALOG_BESTSELLERS',
            Mage::app()->getStore()->getId(),
            Mage::getDesign()->getPackageName(),
            Mage::getDesign()->getTheme('template'),
            Mage::getSingleton('customer/session')->getCustomerGroupId(),
            'template' => $this->getTemplate(),
            $this->getProductsCount(),
            'user_type' => Mage::helper('tgc_cms')->getUserType(),
        );
    }

    public function getCollection()
    {
        if (isset($this->_collection)) {
            return $this->_collection;
        }

        $attributeToSort = $this->_getAttribute();

        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addStoreFilter()
            ->addMinimalPrice()
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addAttributeToSelect($attributeToSort)
            ->addAttributeToFilter($attributeToSort, array('gteq' => 1))
            ->addUrlRewrite();

        $collection->setOrder($attributeToSort, 'asc');

        Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
        $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());
        $collection->getSelect()->limit($this->_getLimit());;

        $this->_collection = $collection;

        return $this->_collection;
    }

    private function _getLimit()
    {
        return max(6, intval($this->getProductsCount()));
    }

    private function _getAttribute()
    {
        $userType = Mage::helper('tgc_cms')->getUserType();

        switch ($userType) {
            case Tgc_Cms_Model_Source_UserType::GUEST:
                $attribute = self::GUEST_ATTRIBUTE;
                break;
            case Tgc_Cms_Model_Source_UserType::LOGGED:
                $attribute = self::AUTHENTICATED_ATTRIBUTE;
                break;
        }

        return $attribute;
    }

    public function shouldShowWidget()
    {
        $displayType = intval($this->getDisplayType());
        if ($displayType == Tgc_Cms_Model_Source_UserType::ALL_USERS) {
            return true;
        }

        return $displayType == Mage::helper('tgc_cms')->getUserType();
    }
}
