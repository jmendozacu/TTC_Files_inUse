<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Podcast_Block_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
    /**
     * Default direction
     *
     * @var string
     */
    protected $_direction           = 'desc';

    /*
     * Years filter
     */
    protected $_years               = array();
    protected $_year_start          = 2013;
    protected $_default_year        = 'All';

    /**
     * GET parameter year variable
     *
     * @var string
     */
    protected $_yearVarName        = 'year';


    public function _construct()
	{
		parent::_construct();
	    // Customize to use our own toolbar.phtml instead of the one from catalog/product
        $this->_template = 'podcast/toolbar.phtml';
        // Override Available orders with our own
        $this->_availableOrder = array(
            'created_time' => "Newest Episodes",
        );
    }

    public function getYearsFilter()
    {
        if(!count($this->_years))
        {
            $this->_years['All'] = $this->__('All');
            $this_year = date('Y');
            // create array of years by counting down from current year to start year
            for($i=$this_year; $i >= $this->_year_start; $i--)
            {
                $this->_years[$i] = $i;
            }
        }
        return $this->_years;
    }

    /**
     * Retrieve Pager URL
     *
     * @param string $order
     * @param string $direction
     * @return string
     */
    public function getOrderUrl($order, $direction, $year = null)
    {
        if (is_null($order)) {
            $order = $this->getCurrentOrder() ? $this->getCurrentOrder() : $this->_availableOrder[0];
        }
        if (is_null($direction)) {
            $direction = $this->getCurrentDirection() ? $this->getCurrentDirection() : 'desc';
        }
        if (is_null($year)) {
            $year = $this->getCurrentYear() ? $this->getCurrentYear() : date('Y');
        }
        return $this->getPagerUrl(array(
            $this->getOrderVarName()=>$order,
            $this->getDirectionVarName()=>$direction,
            $this->getYearVarName() => $year
        ));
    }

    public function isYearCurrent($year)
    {
        return ($year == $this->getCurrentYear());
    }

    /**
     * Retrieve order field GET var name
     *
     * @return string
     */
    public function getYearVarName()
    {
        return $this->_yearVarName;
    }

    /**
     * Get grid year field
     *
     * @return string
     */
    public function getCurrentYear()
    {
        $year = $this->_getData('_current_grid_year');
        if ($year) {
            return $year;
        }

        $years = $this->getYearsFilter();
        $defaultYear = $this->_default_year;

        if (!isset($years[$defaultYear])) {
            $keys = array_keys($years);
            $defaultYear = $keys[0];
        }

        $year = $this->getRequest()->getParam($this->getYearVarName());
        if ($year && isset($years[$year])) {
            if ($year == $defaultYear) {
                Mage::getSingleton('catalog/session')->unsYearFilter();
            } else {
                $this->_memorizeParam('year_filter', $year);
            }
        } else {
            $year = Mage::getSingleton('catalog/session')->getYearFilter();
        }
        // validate session value
        if (!$year || !isset($years[$year])) {
            $year = $defaultYear;
        }
        $this->setData('_current_grid_year', $year);
        return $year;
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

        if ($this->getCurrentOrder()) {
            $this->_collection->setOrder($this->getCurrentOrder(), $this->getCurrentDirection());
        }
        if ($this->getCurrentYear() && $this->getCurrentYear() != 'All')
        {
            $this->_collection->getSelect()->where(
                'YEAR(main_table.created_time) = (?)', $this->getCurrentYear()
            );
        }
        return $this;
    }
}
