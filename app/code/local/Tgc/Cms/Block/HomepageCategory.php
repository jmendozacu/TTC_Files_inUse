<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Block_HomepageCategory extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface
{
    const RESIZE_PATH   = 'cms/category/resized';
    const RESIZE_WIDTH  = 320;
    const RESIZE_HEIGHT = 300;
    const CACHE_TAG     = 'HOMEPAGE_CATEGORY';

    /**
     * Initialize block's cache and template settings
     */
    protected function _construct()
    {
        parent::_construct();

        if (empty($this->_template)) {
            $this->setTemplate('cms/widget/homepageCategory.phtml');
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
            Mage::app()->getStore()->getId(),
            Mage::getDesign()->getPackageName(),
            Mage::getDesign()->getTheme('template'),
            'template' => $this->getTemplate(),
            'user_type' => Mage::helper('tgc_cms')->getUserType(),
        );

        return array_merge($info, parent::getCacheKeyInfo());
    }

    public function getThumbUrl($category)
    {
        if ($image = $category->getThumb()) {
            $path = Mage::getBaseDir('media') . DS . 'catalog/category' . DS . $image;
            $filename = $image;
            $newPath = Mage::getBaseDir('media') . DS . self::RESIZE_PATH . DS . self::RESIZE_WIDTH . 'x' . self::RESIZE_HEIGHT . DS . $filename;

            if (file_exists($path) && is_file($path) && !file_exists($newPath)) {
                $imageObj = new Varien_Image($path);
                $imageObj->constrainOnly(true);
                $imageObj->keepAspectRatio(false);
                $imageObj->keepFrame(false);
                $imageObj->resize(self::RESIZE_WIDTH, self::RESIZE_HEIGHT);
                $imageObj->save($newPath);
            }
            $url = Mage::getBaseUrl('media') . self::RESIZE_PATH . DS . self::RESIZE_WIDTH . 'x' . self::RESIZE_HEIGHT . DS . $filename;
        } else {
            $url = $this->getSkinUrl('images/tgc/category-better.jpg');
        }

        return $url;
    }

    public function getCollection()
    {
        $parent = Mage::app()->getStore()->getRootCategoryId();
        $collection = Mage::getModel('catalog/category')
            ->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('thumb')
            ->addAttributeToFilter('parent_id', $parent)
            ->addIsActiveFilter()
            ->setStore(Mage::app()->getStore())
            ->setOrder('position', 'asc');

        return $collection;
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
