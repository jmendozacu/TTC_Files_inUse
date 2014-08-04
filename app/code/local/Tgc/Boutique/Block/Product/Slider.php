<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Block_Product_Slider extends Tgc_Boutique_Block_Product_Abstract
{
    const CACHE_TAG = 'BOUTIQUE_SLIDER';

    /**
     * Internal constructor
     *
     */
    protected function _construct()
    {
        parent::_construct();

        if (empty($this->_template)) {
            $this->setTemplate('boutique/widget/slider.phtml');
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
            $this->getProductsCount(),
            'is_prospect' => array_key_exists(Tgc_CookieNinja_Model_Ninja::COOKIE_IS_PROSPECT, $_COOKIE) ?
                $_COOKIE[Tgc_CookieNinja_Model_Ninja::COOKIE_IS_PROSPECT] : 'Unknown',
            'ad_code' => array_key_exists(Tgc_CookieNinja_Model_Ninja::COOKIE_AD_CODE, $_COOKIE) ?
                $_COOKIE[Tgc_CookieNinja_Model_Ninja::COOKIE_AD_CODE] : 'Unknown',
        );
    }
}
