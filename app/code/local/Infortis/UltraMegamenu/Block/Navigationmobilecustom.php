<?php
/**
 * Custom block for mobile category drop down
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Infortis
 * @package     UltraMegamenu
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Infortis_UltraMegamenu_Block_Navigationmobilecustom extends Infortis_UltraMegamenu_Block_Navigation
{
    public function _construct()
    {
        parent::_construct();
        $this->assign('urlRequestPath', $this->getCurrentCategoryRequestPath());
        $this->assign('category', $this->getCurrentCategoryObject());
        if($category = $this->getCurrentCategoryObject()) {
            $this->assign('currentCategoryName', $category->getName());
            $childrenCategories = $this->getChildrenCategories($category);
            $this->assign('childrenCategories', $childrenCategories);
            $parentCategory = $category->getParentCategory();
            $this->assign('parentCategory', $parentCategory);
            $this->assign('showParentsChildren', $this->getShowParentsChildren($childrenCategories));
            $this->assign('parentsChildrenCategories', $this->getParentsChildrenCategories($childrenCategories, $parentCategory));
        }
    }

    public function getCurrentDropdownCategoryId()
    {
        return Mage::app()->getRequest()->getParam('id');
    }

    public function getCurrentCategoryRequestPath()
    {
        $urlRequestPath = trim(Mage::app()->getRequest()->getOriginalPathInfo(), '/');
        if (strripos($urlRequestPath,'/')) {
            $urlRequestPath = substr($urlRequestPath, strripos($urlRequestPath,'/'));
        }

        return $urlRequestPath;
    }

    public function getCurrentCategoryObject()
    {
        $currentCategory = false;
        $currentCategoryId = $this->getCurrentDropdownCategoryId();
        if($currentCategoryId) {
            $category = Mage::getModel('catalog/category')->load($currentCategoryId);
            if($category->getId()) {
                $currentCategory = $category;
            }
        }

        return $currentCategory;
    }

    public function getChildrenCategories($category)
    {
        $childrenCategories = $category->getChildrenCategories()->clear()
            ->addAttributeToSelect('include_in_menu')
            ->addAttributeToSelect('url_path')
            ->addAttributeToFilter('include_in_menu', 1)
            ->load();

        return $childrenCategories;
    }

    public function getShowParentsChildren($childrenCategories)
    {
        $showParentsChildren = false;

        if($childrenCategories) {
            if($childrenCategories->count() == 0) {
                $showParentsChildren = true;
            }
        }

        return $showParentsChildren;
    }

    public function getParentsChildrenCategories($childrenCategories, $parentCategory)
    {
        $parentsChildrenCategories = false;
        if($childrenCategories)
        {
            if($childrenCategories->count() == 0) {
                $parentsChildrenCategories = $parentCategory->getChildrenCategories()->clear()
                    ->addAttributeToSelect('include_in_menu')
                    ->addAttributeToSelect('url_path')
                    ->addAttributeToFilter('include_in_menu', 1)->load();
            }
        }

        return $parentsChildrenCategories;
    }

    public function getIsCategorySelected($category)
    {
        $isSelected = false;
        $childCategoryRequestPath = $category->getRequestPath() . ".html";
        if(strripos($childCategoryRequestPath,'/')) {
            $childCategoryRequestPath = substr($childCategoryRequestPath, strripos($childCategoryRequestPath,'/'));
        }

        if($childCategoryRequestPath == $this->getCurrentCategoryRequestPath()) {
            $isSelected = true;
        }

        return $isSelected;
    }
}