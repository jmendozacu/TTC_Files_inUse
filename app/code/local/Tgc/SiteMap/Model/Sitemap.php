<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Tgc_SiteMap
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_SiteMap_Model_Sitemap extends Mage_Core_Model_Abstract
{
    protected $_storeId = null;
    
    /**
     * Get current store id
     * 
     * @return int
     */
    public function getStoreId()
    {
        if ($this->_storeId == null) {
            $this->_storeId = Mage::app()->getStore()->getId();
        }
        
        return $this->_storeId;
    }
    
    /**
     * Get all pages to use for site map
     * 
     * @return array
     */
    public function getPages()
    {
        return Mage::getResourceModel('tgc_sitemap/page')->getCollection($this->getStoreId());
    }
    
    /**
     * Get all products to use for site map
     * 
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getProducts()
    {
        $storeId = $this->getStoreId();
        
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('entity_id')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('url_key')
            ->addAttributeToFilter('status', 1)
            ->addStoreFilter($storeId)
            ->addAttributeToFilter('visibility', array('in' => array(2, 3, 4)))
            ->setOrder('name', 'asc');
        
        return $collection;
    }
    
    /**
     * Get all categories to use for site map
     * 
     * @param Mage_Catalog_Model_Category $category
     * @param string $prefix
     * @return array
     */
    public function getCategories($category = null, $prefix = '')
    {
        /* @var $category Mage_Catalog_Model_Category */
        if ($category == null) {
            $category = Mage::getModel('catalog/category')->load(Mage::app()->getStore()->getRootCategoryId());
        }
        $output = array();
        
        $children = explode(",", $category->getChildren());
        
        $collection = Mage::getModel('catalog/category')->getCollection()
            ->addAttributeToSelect('entity_id')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('url')
            ->addAttributeToFilter('entity_id', array('in' => $children))
            ->setOrder('name', 'asc');
        
        if (count($collection) > 0) {
            foreach ($collection as $item) {
                $output[] = new Varien_Object(array('name' => $prefix . $item->getName(), 'url' => $item->getUrl()));
                $output = array_merge($output, $this->getCategories($item, $prefix . '--'));
            }
        }
        
        return $output;
    }
}
