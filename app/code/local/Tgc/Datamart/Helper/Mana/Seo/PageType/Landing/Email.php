<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Helper_Mana_Seo_PageType_Landing_Email extends Mana_Seo_Helper_PageType
{
    public function getSuffixHistoryType()
    {
        return '';
    }

    /**
     * @param Mana_Seo_Rewrite_Url $urlModel
     * @return string | bool
     */
    public function getUrlKey($urlModel)
    {
        return 'tgc/courses/specialoffer';
    }
}
