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
class Tgc_ManaPro_Model_Filter_AllTypes_Solr_Reverse_Attribute extends Tgc_ManaPro_Model_Filter_AllTypes_Solr_Attribute
{
    /**
     * @param Enterprise_Search_Model_Resource_Collection $collection
     */
    public function applyToCollection($collection)
    {
        $engine = Mage::getResourceSingleton('enterprise_search/engine');
        //replace "On-Sale" option ID by onSale option text for particular customer group and website.
        $values = $this->_prepareValuesForSolrFilter($this->getMSelectedValues());
        $collection->addFqFilter(array($this->getFilterField() => array('reverse' => $values)));
    }

}