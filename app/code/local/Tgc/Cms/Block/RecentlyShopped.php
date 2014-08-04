<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Block_RecentlyShopped extends Mage_Reports_Block_Product_Widget_Viewed
implements Mage_Widget_Block_Interface
{
    const CACHE_TAG = 'RECENTLY_SHOPPED';

    /**
     * Internal constructor
     *
     */
    protected function _construct()
    {
        parent::_construct();

        if (empty($this->_template)) {
            $this->setTemplate('cms/widget/recentlyShopped.phtml');
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
            'CATALOG_PRODUCTS',
            Mage::app()->getStore()->getId(),
            Mage::getDesign()->getPackageName(),
            Mage::getDesign()->getTheme('template'),
            Mage::getSingleton('customer/session')->getCustomerGroupId(),
            'template' => $this->getTemplate(),
            $this->getItemsCollection()->getSize(),
            'user_type' => Mage::helper('tgc_cms')->getUserType(),
        );
    }

    protected function _toHtml()
    {
        if ((!$this->getCount() || $this->getCount() < $this->getMinProductsCount()) && $this->getShowBestsellers()) {
            return $this->getLayout()
                ->createBlock('tgc_cms/bestSellers')
                ->setTitle('Best Sellers')
                ->setDisplayType(Tgc_Cms_Model_Source_UserType::ALL_USERS)
                ->setProductsCount($this->getProductsCount())
                ->setTemplate('cms/widget/bestSellers.phtml')
                ->toHtml();
        } else {
            return parent::_toHtml();
        }
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
