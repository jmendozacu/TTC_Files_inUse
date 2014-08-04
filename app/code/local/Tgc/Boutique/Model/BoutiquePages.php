<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Model_BoutiquePages extends Mage_Core_Model_Abstract
{
    const CACHE_TAG         = 'boutique_pages';

    protected $_eventPrefix = 'boutique_pages';
    protected $_eventObject = 'boutique_pages';

    protected function _construct()
    {
        $this->_init('tgc_boutique/boutiquePages');
    }
}
