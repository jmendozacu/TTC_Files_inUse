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
class Tgc_ManaPro_Model_Filter_AllTypes_Solr_And_Attribute extends Tgc_ManaPro_Model_Filter_AllTypes_Solr_Attribute
{
    /**
     * @param Enterprise_Search_Model_Resource_Collection $collection
     */
    public function applyToCollection($collection)
    {
        $engine = Mage::getResourceSingleton('enterprise_search/engine');
        $facetField = $engine->getSearchEngineFieldName($this->getAttributeModel(), 'nav');
        //replace "On-Sale" option ID by onSale option text for particular customer group and website.
        $values = $this->_prepareValuesForSolrFilter($this->getMSelectedValues());
        $collection->addFqFilter(array($facetField => array('and' => $values)));
    }

    /**
     * @param array $selectedValues
     * @return array
     */
    protected function _prepareValuesForSolrFilter($selectedValues)
    {
        if ($selectedValues) {
            $onSaleOptionId = $this->_getOnSaleProcessor()->getOnSaleOptionId();
            foreach ($selectedValues as $k => $value) {
                if ($onSaleOptionId == $value) {
                    $selectedValues[$k] = $this->_getOnSaleProcessor()->getFilterableValue();
                }
            }
        }
        return $selectedValues;
    }
}