<?php
/**
 * Tgc Catalog
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Catalog_Helper_Mana_Seo_PageType_RootCategory extends Mana_Seo_Helper_PageType
{
    public function getSuffixHistoryType()
    {
        return '';
    }

    /**
     * @param Mana_Seo_Model_ParsedUrl $token
     * @return bool
     */
    public function setPage($token) {
        return parent::setPage($token);
        $token
            ->setIsRedirectToSubcategoryPossible(true)
            ->addParameter('id', Mage::app()->getStore()->getRootCategoryId());
        return true;
    }

    /**
     * @param Mana_Seo_Rewrite_Url $urlModel
     * @return string | bool
     */
    public function getUrlKey($urlModel)
    {
        return 'courses';
    }
}
