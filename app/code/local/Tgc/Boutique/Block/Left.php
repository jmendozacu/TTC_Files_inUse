<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Block_Left extends Mage_Core_Block_Template
{
    private $_page;
    private $_boutique;

    public function getPage()
    {
        if (isset($this->_page)) {
            return $this->_page;
        }

        $this->_page = Mage::registry('boutique_page');

        return $this->_page;
    }

    public function getBoutique()
    {
        if (isset($this->_boutique)) {
            return $this->_boutique;
        }

        $this->_boutique = Mage::registry('current_boutique');

        return $this->_boutique;
    }

    public function getPageCollection()
    {
        $storesFilter = array(
            0, Mage::app()->getStore()->getStoreId(),
        );

        $boutique = $this->getBoutique();
        if ($boutique && $boutique->getId()) {
            $pages = $boutique->getPages();
        } else {
            return array();
        }

        $collection = Mage::getModel('tgc_boutique/boutiquePages')
            ->getCollection()
            ->addFieldToFilter('store_id', array('in' => $storesFilter))
            ->addFieldToFilter('entity_id', array('in' => $pages))
            ->addFieldToSelect('page_title')
            ->addFieldToSelect('url_key')
            ->setOrder('sort_order', 'asc');

        $nav = array();
        foreach ($collection as $page) {
            $nav[] = array(
                'title' => $page->getPageTitle(),
                'url'   => Mage::getUrl(Tgc_Boutique_Controller_Router::BOUTIQUE_MODULE_NAME . '/' . $boutique->getUrlKey() . '/' . $page->getUrlKey()),
            );
        }

        return $nav;
    }

    public function isCurrent(array $item)
    {
        $page = $this->getPage();
        if ($page && $page->getId()) {
            if ($page->getPageTitle() == $item['title']) {
                return true;
            }
        }

        return false;
    }

    public function getBoutiqueName()
    {
        $boutique = $this->getBoutique();
        if ($boutique && $boutique->getId()) {
            return $boutique->getName();
        }

        return '';
    }
}
