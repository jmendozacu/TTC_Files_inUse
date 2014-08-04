<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Block_CategoryHeroCarousel extends Tgc_Cms_Block_HeroCarousel
implements Mage_Widget_Block_Interface
{
    const CACHE_TAG                = 'CATEGORY_HERO_CAROUSEL';
    const HERO_TAB_MAX_LENGTH      = 120;
    const HERO_HEADLINE_MAX_LENGTH = 80;
    const MAX_NUM_TABS             = 4;

    private $_collection;

    /**
     * Initialize block's cache and template settings
     */
    protected function _construct()
    {
        Mage_Core_Block_Template::_construct();

        if (empty($this->_template)) {
            $this->setTemplate('cms/widget/categoryHeroCarousel.phtml');
        }

        $cacheLifetime = $this->getCacheLifetime() ? $this->getCacheLifetime() : false;
        $this->addData(array('cache_lifetime' => $cacheLifetime));
        $this->addCacheTag(array(
            self::CACHE_TAG,
        ));
    }

    /**
     * Get Key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $info = array(
            'category'    => Mage::registry('current_category')->getId(),
            'user_type'   => Mage::helper('tgc_cms')->getUserType(),
        );

        return array_merge(parent::getCacheKeyInfo(), $info);
    }

    public function getCollection()
    {
        if (isset($this->_collection)) {
            return $this->_collection;
        }

        $userType = Mage::helper('tgc_cms')->getUserType();
        $category = Mage::registry('current_category');
        $request = Mage::app()->getRequest();
        if (Mage::registry('sale_category_id')) {
            $categoryId = Mage::registry('sale_category_id');
        } elseif($request->getParam('category') == 'view' && $request->getParam('id')) {
            $categoryId = $request->getParam('id');
            $category = Mage::getModel('catalog/category')->load($categoryId);
            if($category->getId()) {
                Mage::register('current_category', $category);
            }
        } else {
            $categoryId = $category->getId();
        }
        $collection = Mage::getResourceModel('tgc_cms/categoryHeroCarousel_collection')
            ->addFieldToSelect('*')
            ->addFieldToFilter('store', array('in' => array(0, Mage::app()->getStore()->getId())))
            ->addFieldToFilter('is_active', array('eq' => 1))
            ->addFieldToFilter('user_type', array('in' => array($userType, Tgc_Cms_Model_Source_UserType::ALL_USERS)))
            ->addFieldToFilter('active_from',
            array(
                array('to' => Mage::getModel('core/date')->gmtDate()),
                array('active_from', 'null' => ''))
        )
            ->addFieldToFilter('active_to',
            array(
                array('gteq' => Mage::getModel('core/date')->gmtDate()),
                array('active_to', 'null' => ''))
        )
            ->addFieldToFilter('category_id', array('eq' => $categoryId))
            ->setOrder('sort_order', 'asc');
        $collection->getSelect()->limit(self::MAX_NUM_TABS);

        $this->_collection = $collection;

        return $this->_collection;
    }

    /**
     * Returns Hero Image URL of the current category
     *
     * @return string
     */
    public function getCategoryImage()
    {
        $category = Mage::registry('current_category');
        $url = false;
        if (!is_null($category) && $category->getId()) {
            if ($image = $category->getHeroImage()) {
                $url = Mage::getBaseUrl('media').'catalog/category/'.$image;
            }
        }

        return $url;
    }

    /**
     * Returns Hero Color of the current category
     *
     * @return string
     */
    public function getCategoryColor()
    {
        $category = Mage::registry('current_category');
        if (!is_null($category) && $category->getId()) {
            return $category->getHeroColor();
        }

        return false;
    }
}
