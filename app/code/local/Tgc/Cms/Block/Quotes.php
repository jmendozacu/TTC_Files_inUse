<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Block_Quotes extends Mage_Core_Block_Template
implements Mage_Widget_Block_Interface
{
    const CACHE_TAG = 'QUOTES_WIDGET';

    /**
     * Initialize block's cache and template settings
     */
    protected function _construct()
    {
        parent::_construct();

        if (empty($this->_template)) {
            $this->setTemplate('cms/widget/quotes.phtml');
        }

        $cacheLifetime = $this->getCacheLifetime() ? $this->getCacheLifetime() : false;
        $this->addData(array('cache_lifetime' => $cacheLifetime));
        $this->addCacheTag(array(
            self::CACHE_TAG,
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

        return array_merge($info, parent::getCacheKeyInfo());
    }

    public function getCollection()
    {
        $collection = Mage::getResourceModel('tgc_cms/quotes_collection')
            ->addFieldToSelect('*')
            ->addFieldToFilter('store', array('in' => array(0, Mage::app()->getStore()->getId())))
            ->addFieldToFilter('is_active', array('eq' => 1));

        if ($this->getUseSortOrder()) {
            $collection->setOrder('sort_order', 'asc');
        } else {
            $collection->getSelect()->order(new Zend_Db_Expr('RAND()'));
        }

        $collection->getSelect()->limit(3);

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
