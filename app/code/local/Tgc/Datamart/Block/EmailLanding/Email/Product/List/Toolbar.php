<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Block_EmailLanding_Email_Product_List_Toolbar extends Tgc_Catalog_Block_Product_List_Toolbar
{
    protected $_orderField = 'position';
    protected $_direction  = 'asc';

    public function _construct()
    {
        parent::_construct();
        $this->addOrderToAvailableOrders('position', 'Best Value');
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
            if (Mage::getSingleton('customer/session')->isLoggedIn()) {
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
            if (in_array($order, array('price', 'name'))) {
                $dir = $this->getCurrentDirection();

                $newOrder = $order . '_' . $dir;

                if ($newOrder && isset($orders[$newOrder])) {
                    if ($order == $defaultOrder) {
                        Mage::getSingleton('catalog/session')->unsSortOrder();
                    } else {
                        $this->_memorizeParam('sort_order', $order);
                    }
                }
            }
        }

        if ($newOrder != '') {
            if (!$newOrder || !isset($orders[$newOrder])) {
                $order = $defaultOrder;
            }
        } else {
            if (!$order || !isset($orders[$order])) {
                $order = $defaultOrder;
            }
        }

        $this->setData('_current_grid_order', $order);
        return $order;
    }

    public function getCurrentDirection()
    {
        $dir = $this->_getData('_current_grid_direction');
        if ($dir) {
            return $dir;
        }

        $directions = array('asc', 'desc');
        $dir = strtolower($this->getRequest()->getParam($this->getDirectionVarName()));
        if ($dir && in_array($dir, $directions)) {
            if ($dir == $this->_direction) {
                Mage::getSingleton('catalog/session')->unsSortDirection();
            } else {
                $this->_memorizeParam('sort_direction', $dir);
            }
        }
        // validate direction
        if (!$dir || !in_array($dir, $directions)) {
            $dir = $this->_direction;
        }
        $this->setData('_current_grid_direction', $dir);
        return $dir;
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
                /** @var $resource Tgc_Datamart_Model_Resource_EmailLanding */
                $resource = Mage::getResourceModel('tgc_datamart/emailLanding');
                $resource->addSortOrderToCollection($collection, $this->getLandingPageCategory());
                $this->_collection->getSelect()->order('landing_position');
            } else {
                if (Mage::helper('tgc_solr')->isSolr()) {
                    $this->_collection->setOrder('attribute_set_id', 'asc');
                } else {
                    $this->_collection->addAttributeToSort('attribute_set_id', 'asc');
                }
                $this->_collection->setOrder($this->getCurrentOrder(), $this->getCurrentDirection());
            }
        }
        return $this;
    }

    /**
     * Landing page category getter
     *
     * @return string
     */
    public function getLandingPageCategory()
    {
        if (!$this->hasData('landing_page_category')) {
            $this->setLandingPageCategory(Mage::registry('landing_page_category'));
        }

        return $this->getData('landing_page_category');
    }
}
