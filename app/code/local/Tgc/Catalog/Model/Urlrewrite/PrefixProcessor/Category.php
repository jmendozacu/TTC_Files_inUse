<?php
/**
 * Category URL prefix processor
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Catalog_Model_Urlrewrite_PrefixProcessor_Category
    extends Tgc_Catalog_Model_Urlrewrite_PrefixProcessor_Abstract
{
    /**
     * Returns available category prefixes
     *
     * @param int $storeId
     * @return array<string>
     */
    protected function _getPrefixes($storeId)
    {
        $url = Mage::getSingleton('catalog/factory')->getCategoryUrlInstance();

        return ($url instanceof Tgc_Catalog_Model_Category_Url)
            ? $url->getPrefixes($storeId)
            : array();
    }
}