<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Model_BoutiqueHeroCarousel extends Mage_Core_Model_Abstract
{
    const CACHE_TAG         = 'boutique_hero_carousel';

    protected $_eventPrefix = 'boutique_hero_carousel';
    protected $_eventObject = 'boutique_hero_carousel';

    protected function _construct()
    {
        $this->_init('tgc_boutique/boutiqueHeroCarousel');
    }
}
