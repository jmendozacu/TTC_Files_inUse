<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @boutique    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Block_BoutiqueHeroCarousel extends Mage_Core_Block_Template
implements Mage_Widget_Block_Interface
{
    const CACHE_TAG                = 'BOUTIQUE_HERO_CAROUSEL';
    const HERO_TAB_MAX_LENGTH      = 120;
    const HERO_HEADLINE_MAX_LENGTH = 80;
    const MAX_NUM_TABS             = 4;
    const MIN_NUM_TABS             = 2;

    private $_collection;

    protected function _construct()
    {
        Mage_Core_Block_Template::_construct();

        if (empty($this->_template)) {
            $this->setTemplate('boutique/widget/heroCarousel.phtml');
        }

        $cacheLifetime = $this->getCacheLifetime() ? $this->getCacheLifetime() : false;
        $this->addData(array('cache_lifetime' => $cacheLifetime));
        $this->addCacheTag(array(
            self::CACHE_TAG,
        ));
    }

    public function getCacheKeyInfo()
    {
        $info = array(
            'boutique'      => Mage::registry('current_boutique')->getId(),
            'boutique_page' => Mage::registry('boutique_page')->getId(),
            'user_type'     => Mage::helper('tgc_boutique')->getUserType(),
        );

        return array_merge(parent::getCacheKeyInfo(), $info);
    }

    public function getCollection()
    {
        if (isset($this->_collection)) {
            return $this->_collection;
        }

        $userType = Mage::helper('tgc_boutique')->getUserType();
        $boutique = Mage::registry('current_boutique');
        $page = Mage::registry('boutique_page');

        $collection = Mage::getResourceModel('tgc_boutique/boutiqueHeroCarousel_collection')
            ->addFieldToSelect('*')
            ->addFieldToFilter('store', array('in' => array(0, Mage::app()->getStore()->getId())))
            ->addFieldToFilter('is_active', array('eq' => 1))
            ->addFieldToFilter('user_type', array('in' => array($userType, Tgc_Boutique_Model_Source_UserType::ALL_USERS)))
            ->addFieldToFilter('active_from',
            array(
                array('to' => Mage::getModel('core/date')->gmtDate()),
                array('active_from', 'null' => ''))
        )
            ->addFieldToFilter('active_to',
            array(
                array('gteq' => Mage::getModel('core/date')->gmtDate()),
                array('active_to', 'null' => ''))
        )
            ->addFieldToFilter('boutique_id', array('in' => array(0, $boutique->getEntityId())))
            ->addFieldToFilter('boutique_page_id', array('in' => array(0, $page->getEntityId())))
            ->setOrder('sort_order', 'asc');
        $collection->getSelect()->limit(self::MAX_NUM_TABS);
        $this->_collection = $collection;
        
        if ($collection->getSize() < self::MIN_NUM_TABS) {
            return new Varien_Data_Collection;
        }

        return $this->_collection;
    }

    public function shouldShowWidget()
    {
        $boutique = Mage::registry('current_boutique');
        $page = Mage::registry('boutique_page');

        if (!$boutique || !$page || $boutique->getDisableCarousel() || $page->getDisableCarousel()) {
            return false;
        }

        $displayType = intval($this->getDisplayType());
        if ($displayType == Tgc_Boutique_Model_Source_UserType::ALL_USERS) {
            return true;
        }

        return $displayType == Mage::helper('tgc_boutique')->getUserType();
    }
}
