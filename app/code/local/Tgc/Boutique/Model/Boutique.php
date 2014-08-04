<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Model_Boutique extends Mage_Core_Model_Abstract
{
    const CACHE_TAG         = 'boutique';

    protected $_eventPrefix = 'boutique';
    protected $_eventObject = 'boutique';

    protected function _construct()
    {
        $this->_init('tgc_boutique/boutique');
    }

    protected function _beforeSave()
    {
        $pages = $this->getPages();
        if (empty($pages)) {
            $pages = array();
        } else if (!is_array($pages)) {
            $pages = array($pages);
        }

        $pages = serialize($pages);

        $this->setPages($pages);

        if ($this->getIsDefault()) {
            $this->getResource()->clearDefaults();
        }
    }

    protected function _afterLoad()
    {
        $pages = $this->getPages();

        $this->setPages(unserialize($pages));
    }
}
