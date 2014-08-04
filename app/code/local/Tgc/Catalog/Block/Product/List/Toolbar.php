<?php
/**
 * Setup resource
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Catalog_Block_Product_List_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
    protected $_defaultProspectOrder = Tgc_Cms_Block_BestSellers::GUEST_ATTRIBUTE;

    public function _construct()
    {
        parent::_construct();
        $availableOrders = array(
            'news_from_date'    => 'Newest',
            Mage::helper('tgc_customer')->getAttributeBestSellerByUserType() => 'Best Sellers',
            'price_asc'         => 'Price low to high',
            'price_desc'        => 'Price high to low',
            'inline_rating'     => 'Customer Ratings',
            'name_asc'          => 'Title: A to Z',
            'name_desc'         => 'Title: Z to A',
        );
        $this->_availableOrder = $availableOrders;
        //Mage::app()->getLayout()->getBlock('product_list')->setAvailableOrders($availableOrders);
    }

    protected function _getDirectionFromOrderName($order)
    {
        if (($position = strrpos('_', $order)) !== false) {
            $dir = strtolower(substr($order, $position));
            if ($dir == 'asc' || $dir == 'desc') {
                return $dir;
            }
        } elseif ($order == Mage::helper('tgc_customer')->getAttributeBestSellerByUserType()) {
            return 'asc';
        }
        return 'desc';
    }

    /**
     * Retrieve current View mode
     *
     * @return string
     */
    public function getCurrentMode()
    {
        $mode = $this->_getData('_current_grid_mode');
        if ($mode) {
            return $mode;
        }
        $modes = array_keys($this->_availableMode);
        $defaultMode = current($modes);
        $mode = $this->getRequest()->getParam($this->getModeVarName());

        //Different pages have different default list modes. For example, professor = list and rest of site = grid.
        //Since mode information is stored to session this function useful for clearing session data when needed and retrieving relevant data when necessary.
        $mode = $this->_helperTgcCatalog()->retrieveModeFromCustomModeSwitcher($mode, $defaultMode);

        if ($mode) {
            if ($mode == $defaultMode) {
                Mage::getSingleton('catalog/session')->unsDisplayMode();
            } else {
                $this->_memorizeParam('display_mode', $mode);
            }
        } else {
            $mode = Mage::getSingleton('catalog/session')->getDisplayMode();
        }

        if (!$mode || !isset($this->_availableMode[$mode])) {
            $mode = $defaultMode;
        }
        $this->setData('_current_grid_mode', $mode);
        return $mode;
    }

    public function _helperTgcCatalog()
    {
        return Mage::helper('tgc_catalog');
    }

    /**
     * Get grit products sort order field
     *
     * @return string
     */
    public function getCurrentOrder()
    {
        $order = $this->_getData('_current_grid_order');
        if ($order) {
            return $order;
        }

        $orders = $this->_availableOrder;

        $defaultOrder = $this->_orderField;

        if (!$defaultOrder || !isset($orders[$defaultOrder])) {
            if (Mage::helper('tgc_customer')->getUserType() == Tgc_Cms_Model_Source_UserType::LOGGED) {
                $keys = array_keys($orders);
                $defaultOrder = $keys[0];
            } else {
                $defaultOrder = $this->_defaultProspectOrder;
            }
        }

        $newOrder = '';
        $order = $this->getRequest()->getParam($this->getOrderVarName());
        if ($order && isset($orders[$order])) {
            if ($order == $defaultOrder) {
                Mage::getSingleton('catalog/session')->unsSortOrder();
            } else {
                $this->_memorizeParam('sort_order', $order);
            }
        } else {
            if (!$order) {
                $order = Mage::getSingleton('catalog/session')->getSortOrder();
            }
            if (in_array($order, array('price', 'name'))) {
                $dir = $this->getCurrentDirection();

                $newOrder = $order . '_' . $dir;

                if ($newOrder && isset($orders[$newOrder])) {
                    if ($order == $defaultOrder) {
                        Mage::getSingleton('catalog/session')->unsSortOrder();
                    } else {
                        $this->_memorizeParam('sort_order', $order);
                    }
                } else {
                    $order = Mage::getSingleton('catalog/session')->getSortOrder();
                }
            }
        }
        // validate session value
        if ($newOrder != '') {
            if (!$newOrder || !isset($orders[$newOrder])) {
                $order = $defaultOrder;
                $dir = $this->_getDirectionFromOrderName($order);
                $this->setData('_current_grid_direction', $dir);
            }
        } else {
            if (!$order || !isset($orders[$order])) {
                $order = $defaultOrder;
                $dir = $this->_getDirectionFromOrderName($order);
                $this->setData('_current_grid_direction', $dir);
            }
        }

        $this->setData('_current_grid_order', $order);
        return $order;
    }

    /**
     * Compare defined order field vith current order field
     *
     * @param string $order
     * @param string $dir
     * @return bool
     */
    public function isOrderCurrent($order, $dir = '')
    {
        return ($order == $this->getCurrentOrder() && $dir == $this->getCurrentDirection());
    }

    /**
     * Set collection to pager
     *
     * @param Varien_Data_Collection $collection
     * @return Mage_Catalog_Block_Product_List_Toolbar
     */
    public function setCollection($collection)
    {
        $this->_collection = $collection;

        $this->_collection->setCurPage($this->getCurrentPage());

        // we need to set pagination only if passed value integer and more that 0
        $limit = (int)$this->getLimit();
        if ($limit) {
            $this->_collection->setPageSize($limit);
        }
        if ($this->getCurrentOrder()) {
            if ($this->getCurrentOrder() == 'position') {
                $this->_collection->setOrder('attribute_set_id', 'asc');
            } else {
                if (Mage::helper('tgc_solr')->isSolr()) {
                    if ($this->getCurrentOrder() == 'relevance') {
                        $queryText = trim(Mage::helper('catalogsearch')->getQuery()->getQueryText());
                        if (is_numeric($queryText)) {
                            //show found by course_id first
                            $this->_collection->setOrder('course_id_map:'.$queryText, 'desc');
                        }
                    }
                    $this->_collection->setOrder('attribute_set_id', 'asc');
                } else {
                    $this->_collection->addAttributeToSort('attribute_set_id', 'asc');
                }
            }
            $this->_collection->setOrder($this->getCurrentOrder(), $this->getCurrentDirection());
        }
        return $this;
    }
}