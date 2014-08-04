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
class Tgc_ManaPro_Model_Filter_AllTypes_Solr_Attribute extends Mana_Filters_Model_Solr_Attribute
{
    /**
     * @var Tgc_ManaPro_Model_Filter_AllTypes_Solr_AttributeOption_OnSale
     */
    private $_onSaleProcessor;

    /**
     * @return Tgc_ManaPro_Model_Filter_AllTypes_Solr_AttributeOption_OnSale
     */
    protected function _getOnSaleProcessor()
    {
        if (!$this->_onSaleProcessor) {
            $this->_onSaleProcessor = Mage::getModel('tgc_manapro/filter_allTypes_solr_attributeOption_onSale',
                array('attribute' => $this->getAttributeModel())
            );
        }
        return $this->_onSaleProcessor;
    }

    public function processCounts($counts)
    {
        /* @var $collection Enterprise_Search_Model_Resource_Collection */
        $collection = $counts;

        $result = $collection->getFacetedData($this->getFilterField(), array('fields' => array('id')));
        $engine = Mage::getResourceSingleton('enterprise_search/engine');
        if (method_exists($engine, 'getSearchEngineFieldName')) {
            $onSaleProcessor = $this->_getOnSaleProcessor();
            $onSaleSolrOptionText = $onSaleProcessor->getFilterableValue();
            $optionId = $onSaleProcessor->getOnSaleOptionId();
            if (isset($result[$onSaleSolrOptionText])) {
                $result[$optionId] = $result[$onSaleSolrOptionText];
                unset($result[$onSaleSolrOptionText]);
            } else {
                $result[$optionId] = 0;
            }
            return $result;
        }
        else {
            $attribute = $this->getAttributeModel();
            $options = $attribute->getFrontend()->getSelectOptions();
            $idResult = array();
            foreach ($options as $option) {
                if (!$option || is_array($option['value'])) {
                    continue;
                }
                if (isset($result[$option['label']])) {
                    $idResult[$option['value']] = $result[$option['label']];
                }
            }
            return $idResult;
        }
    }

    /**
     * @param Enterprise_Search_Model_Resource_Collection $collection
     * @return Enterprise_Search_Model_Resource_Collection
     */
    public function countOnCollection($collection)
    {
        $attribute = $this->getAttributeModel();
        $options = $attribute->getSource()->getAllOptions();
        $facetAttributeValues = array();
        foreach ($options as $option) {
            if (!empty($option['value'])) {
                $facetAttributeValues[] = $option['value'];
            }
        }

        //add current onSale option value for getting counts
        $facetAttributeValues[] = $this->_getOnSaleProcessor()->getFilterableValue();

        $collection->setFacetCondition($this->getFilterField(), $facetAttributeValues);

        return $collection;
    }

    /**
     * @param Enterprise_Search_Model_Resource_Collection $collection
     */
    public function applyToCollection($collection)
    {
        $values = $this->getMSelectedValues();
        $engine = Mage::getResourceSingleton('enterprise_search/engine');
        if (!method_exists($engine, 'getSearchEngineFieldName')) {
            $labels = array();
            foreach ($values as $value) {
                $labels[] = $this->getAttributeModel()->getFrontend()->getOption($value);
            }
            $values = $labels;
            $collection->addFqFilter(array($this->getFilterField() => array('or' => $values)));
        } else {
            //replace "On-Sale" option ID by onSale option text for particular customer group and website.
            $values = $this->_prepareValuesForSolrFilter($values);
            $collection->addFqFilter(array($this->getFilterField() => array('and' => $values)));
        }
    }

    /**
     * @param array $selectedValues
     * @return array
     */
    protected function _prepareValuesForSolrFilter($selectedValues)
    {
        $result = array();
        if ($selectedValues) {
            $andFilters = array();
            $orFilters = array();
            foreach ($selectedValues as $k => $value) {
                $optionProcessor = Mage::getSingleton('tgc_manapro/filter_allTypes_solr_attributeOption_factory')
                    ->getOptionProcessor($this->getAttributeModel(), $value);
                $filterableValue = $optionProcessor->getFilterableValue();
                if ($optionProcessor->isForAndOperand()) {
                    $andFilters[] = $filterableValue;
                } else {
                    $orFilters[] = $filterableValue;
                }
            }
            if ($orFilters) {
                $result['or'] = $orFilters;
            }
            if ($andFilters) {
                $result['and'] = $andFilters;
            }
        }
        return $result;
    }
}