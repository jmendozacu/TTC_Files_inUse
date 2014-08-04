<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Solr
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Solr_Model_Resource_Collection extends Mana_Filters_Resource_Solr_Collection
{
    private $_countsByAttributeSet;

    protected function _setAttributeSetFacetCondition()
    {
        $attrSetName = $this->_getAttributeSetSearchEngineName();
        if (!array_key_exists($attrSetName, $this->_facetedConditions)) {
            $this->setFacetCondition($attrSetName);
        }
    }

    /**
     * Load faceted data if not loaded
     *
     * @param array $additionalParams
     * @return Enterprise_Search_Model_Resource_Collection
     */
    public function loadFacetedData($additionalParams = array())
    {
        $this->_setAttributeSetFacetCondition();
        return parent::loadFacetedData($additionalParams);
    }

    public function getFoundCoursesCount()
    {
        return $this->_getFoundCountsByAttributeSet(Mage::helper('tgc_catalog')->getCourseAttributeSetId());
    }

    public function getFoundSetsCount()
    {
        return $this->_getFoundCountsByAttributeSet(Mage::helper('tgc_catalog')->getSetAttributeSetId());
    }

    protected function _getAttributeSetSearchEngineName()
    {
        return $this->_engine->getSearchEngineFieldName('attribute_set_id', 'sort');
    }

    protected function _getFoundCountsByAttributeSet($attributeSetId)
    {
        if (!isset($this->_countsByAttributeSet)) {
            $this->load();
            $attributeSetName = $this->_getAttributeSetSearchEngineName();
            if (isset($this->_facetedData[$attributeSetName])) {
                $this->_countsByAttributeSet = $this->_facetedData[$attributeSetName];
            } else {
                $this->_countsByAttributeSet = array();
            }
        }
        return isset($this->_countsByAttributeSet[$attributeSetId]) ? $this->_countsByAttributeSet[$attributeSetId] : 0;
    }

    /**
     * Search documents by query
     * Set found ids and number of found results
     *
     * @return Enterprise_Search_Model_Resource_Collection
     */
    protected function _beforeLoad()
    {
        if ($this->_engine) {
            $this->_setAttributeSetFacetCondition();
        }

        return parent::_beforeLoad();
    }
}