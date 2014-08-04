<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Block_NewProducts extends Mage_Catalog_Block_Product_New
implements Mage_Widget_Block_Interface
{
    const CACHE_TAG = 'NEW_PRODUCT_WIDGET';

    /**
     * Initialize block's cache and template settings
     */
    protected function _construct()
    {
        parent::_construct();

        if (empty($this->_template)) {
            $this->setTemplate('cms/widget/newProducts.phtml');
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
            'CATALOG_NEWPRODUCTS',
            Mage::app()->getStore()->getId(),
            Mage::getDesign()->getPackageName(),
            Mage::getDesign()->getTheme('template'),
            Mage::getSingleton('customer/session')->getCustomerGroupId(),
            'template' => $this->getTemplate(),
            $this->getProductsCount(),
            'user_type' => Mage::helper('tgc_cms')->getUserType(),
        );
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
