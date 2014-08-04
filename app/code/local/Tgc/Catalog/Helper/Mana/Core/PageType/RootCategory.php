<?php
/**
 * Tgc Catalog
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Catalog_Helper_Mana_Core_PageType_RootCategory extends Mana_Core_Helper_PageType
{
    public function getCurrentSuffix()
    {
        return '';
    }

    public function getRoutePath()
    {
        return 'courses/index/index';
    }
}
