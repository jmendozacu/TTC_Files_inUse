<?php
/**
 * User: mhidalgo
 * Date: 14/03/14
 * Time: 10:03
 */
class Tgc_Cms_Model_CategoryHeroCarousel extends Mage_Core_Model_Abstract
{
    const CACHE_TAG         = 'category_hero_carousel';

    protected $_eventPrefix = 'category_hero_carousel';
    protected $_eventObject = 'category_hero_carousel';

    protected function _construct()
    {
        $this->_init('tgc_cms/categoryHeroCarousel');
    }
}