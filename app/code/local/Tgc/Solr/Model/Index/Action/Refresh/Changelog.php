<?php
/**
 * Reindexes Solr data by catalogsearch_fulltext changelog
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Solr_Model_Index_Action_Refresh_Changelog
    extends Tgc_Solr_Model_Index_Action_Refresh
{
    /**
     * Refresh entities
     *
     * @return Tgc_Solr_Model_Index_Action_Refresh_Changelog
     * @throws Enterprise_Index_Model_Action_Exception
     */
    public function execute()
    {
        $this->_validate();

        $changedIds = $this->_selectChangedIds();
        if (!empty($changedIds)) {
            $stores = Mage::app()->getStores();
            foreach ($stores as $store) {
                $idsBatches = array_chunk($changedIds, Mage::helper('enterprise_index')->getBatchSize());
                foreach ($idsBatches as $ids) {
                    $this->_reindex($store->getId(), $ids);
                }
            }
            $this->_setChangelogValid();
        }

        return $this;
    }
}
