<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Block_HeroCarousel extends Mage_Core_Block_Template
implements Mage_Widget_Block_Interface
{
    const CACHE_TAG = 'HOMEPAGE_HEROCAROUSEL';

    /**
     * Initialize block's cache and template settings
     */
    protected function _construct()
    {
        parent::_construct();

        if (empty($this->_template)) {
            $this->setTemplate('cms/widget/heroCarousel.phtml');
        }

        $this->addData(array(
            'cache_lifetime' => 120,
            'cache_tags'     => array(self::CACHE_TAG),
        ));
    }

    /**
     * Get Key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $info = array(
            Mage::app()->getStore()->getId(),
            Mage::getDesign()->getPackageName(),
            Mage::getDesign()->getTheme('template'),
            'template' => $this->getTemplate(),
            'user_type' => Mage::helper('tgc_cms')->getUserType(),
        );

        return array_merge(parent::getCacheKeyInfo(), $info);
    }

    public function getCollection()
    {
        $userType = Mage::helper('tgc_cms')->getUserType();

        $collection = Mage::getResourceModel('tgc_cms/heroCarousel_collection')
            ->addFieldToSelect('*')
            ->addFieldToFilter('store', array('in' => array(0, Mage::app()->getStore()->getId())))
            ->addFieldToFilter('is_active', array('eq' => 1))
            ->addFieldToFilter('user_type', array('in' => array($userType, Tgc_Cms_Model_Source_UserType::ALL_USERS)))
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
            ->setOrder('sort_order', 'asc');
        $collection->getSelect()->limit(4);

        return $collection;
    }

    public function shouldShowWidget()
    {
        $displayType = intval($this->getDisplayType());
        if ($displayType == Tgc_Cms_Model_Source_UserType::ALL_USERS) {
            return true;
        }

        return $displayType == Mage::helper('tgc_cms')->getUserType();
    }
}
