<?php
/**
 * @category    Mana
 * @package     Mana_Filters
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
/**
 * @author Mana Team
 *
 */
class Tgc_ManaPro_Model_Solr_Price extends Mana_Filters_Model_Solr_Price
{
    /**
     * Applies counting query to the current collection. The result should be suitable to processCounts() method.
     * Typically, this method should return final result - option id/count pairs for option lists or
     * min/max pair for slider. However, in some cases (like not applied Solr facets) this method returns collection
     * object and later processCounts() extracts actual counts from this collections.
     *
     * @param Enterprise_Search_Model_Resource_Collection $collection
     * @return mixed
     */
    public function countOnCollection($collection)
    {
        if (Mage::app()->getStore()->getConfig(self::XML_PATH_RANGE_CALCULATION) == 'improved') {
            return $this->_addCalculatedFacetConditionToCollection($collection);
        }

        $this->_facets = array();
        $range = $this->getPriceRange();
        $maxPrice = $this->getMaxPriceInt();
        if ($maxPrice > 0) {
            $priceFacets = array();
            $facetCount  = ceil($maxPrice / $range);
            $realFacetCount = $facetCount;
            $itemsCountLimit = $this->getFilterOptions()->getShowMoreItemCount();
            if ($itemsCountLimit > 0 && $facetCount > $itemsCountLimit) {
                $realFacetCount = $itemsCountLimit - 1;
            }

            for ($i = 0; $i < $realFacetCount; $i++) {
                $separator = array($i * $range, ($i + 1) * $range);
                $facetedRange = $this->_prepareFacetRange($separator[0], $separator[1]);
                $this->_facets[$facetedRange['from'] . '_' . $facetedRange['to']] = $separator;
                $priceFacets[] = $facetedRange;
            }

            if ($realFacetCount < $facetCount) {
                $facetedRange = $this->_prepareFacetRange($realFacetCount*$range, '');
                $this->_facets[$facetedRange['from'] . '_'] =
                    array($realFacetCount*$range, $this->getMaxPriceInt());
                $priceFacets[] = $facetedRange;

                Mage::unregister('manadev_max_ranges');
                Mage::register('manadev_max_ranges', array('from' => $realFacetCount*$range, 'to' => ''));
            }

            $collection->setFacetCondition($this->_getFilterField(), $priceFacets);
        }

        return $collection;
    }

    public function processCounts($counts) {
        /* @var $collection Enterprise_Search_Model_Resource_Collection */
        $collection = $counts;

        $fieldName = $this->_getFilterField();
        $facets = $collection->getFacetedData($fieldName);
        $result = array();
        if (!empty($facets)) {
            foreach ($facets as $key => $value) {
                preg_match('/TO ([\d\.\*]+)\]$/', $key, $rangeKey);
                $rangeKey = $rangeKey[1] / $this->getPriceRange();
                $rangeKey = round($rangeKey);
                /** @noinspection PhpIllegalArrayKeyTypeInspection */
                $result[$rangeKey] = $value;
            }
        }
        return $result;
    }

    /**
     * Applies filter values provided in URL to a given product collection
     *
     * @param Enterprise_Search_Model_Resource_Collection $collection
     * @return void
     */
    public function applyToCollection($collection)
    {
        $field             = $this->_getFilterField();
        $fq = array();
        foreach ($this->getMSelectedValues() as $selection) {
            if (strpos($selection, ',') !== false) {
                list($index, $range) = explode(',', $selection);

                if ($index == 0) {
                    $range = Mage::registry('manadev_max_ranges');
                    $fq[] = array(
                        'from' => $range['from'],
                        'to' => ''
                    );
                } else {
                    $range = $this->_getResource()->getPriceRange($index, $range);
                    $to = $range['to'];
                    if ($to < $this->getMaxPriceInt() && !$this->isUpperBoundInclusive()) {
                        $to -= 0.001;
                    }

                    $fq[] = array(
                        'from' => $range['from'],
                        'to'   => $to,
                    );
                }
            }
        }

        $collection->addFqFilter(array($field => array('or' => $fq)));
    }

    /**
     * Prepare text of item label
     *
     * @param   int $range
     * @param   float $value
     * @return  string
     */
    protected function _renderItemLabel($range, $value)
    {
        $itemsCountLimit = $this->getFilterOptions()->getShowMoreItemCount();
        if ($itemsCountLimit && $value == 0) {
            $range = array('from' => ($itemsCountLimit-1) * $range, 'to' => $this->getMaxPriceInt());
        } else {
            $range = $this->_getResource()->getPriceRange($value, $range);
            $range['to'] = $range['to'] - 0.01;
        }
        $result = new Varien_Object();
        Mage::dispatchEvent('m_render_price_range', array('range' => $range, 'model' => $this, 'result' => $result));
        if ($result->getLabel()) {
            return $result->getLabel();
        }
        else {
            $store      = Mage::app()->getStore();
            $fromPrice  = $store->formatPrice($range['from'], false);
            $toPrice    = $store->formatPrice($range['to'], false);
            return Mage::helper('catalog')->__('%s - %s', $fromPrice, $toPrice);
        }
    }
}
