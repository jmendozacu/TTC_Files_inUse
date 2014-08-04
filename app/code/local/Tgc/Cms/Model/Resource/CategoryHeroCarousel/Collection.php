<?php
/**
 * User: mhidalgo
 * Date: 14/03/14
 * Time: 10:05
 */
class Tgc_Cms_Model_Resource_CategoryHeroCarousel_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_cms/categoryHeroCarousel');
    }
}