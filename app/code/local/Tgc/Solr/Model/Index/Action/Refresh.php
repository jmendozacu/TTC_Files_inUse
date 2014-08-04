<?php
/**
 * Model for full reindex of Solr data.
 * We don't need it, because when we run full reindex, then for Solr it is executed by Magento Out-of-box.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Solr_Model_Index_Action_Refresh
    extends Enterprise_Index_Model_Action_Abstract
{
    /**
     * Execute solr reindex
     *
     * @return Tgc_Solr_Model_Index_Action_Refresh
     * @throws Enterprise_Index_Model_Action_Exception
     */
    public function execute()
    {
        //don't need to reindex everything, because Out-Of-Box Magento reindexes Solr data in its code separately.
        if ($this->_metadata->getStatus() == Enterprise_Mview_Model_Metadata::STATUS_INVALID) {
            //need to reindex all only if status is invalid.
            try {
                $this->_getCurrentVersionId();
                $this->_metadata->setInProgressStatus()->save();
                $stores = Mage::app()->getStores();
                foreach ($stores as $store) {
                    $this->_reindex($store->getId());
                }
                $this->_setChangelogValid();
            } catch (Exception $e) {
                $this->_metadata->setInvalidStatus()->save();
                throw new Enterprise_Index_Model_Action_Exception($e->getMessage(), $e->getCode(), $e);
            }
        }
        return $this;
    }

    /**
     * Indexes product's data in Solr.
     *
     * @param int $storeId
     * @param array $changedIds
     *
     * @return Tgc_Solr_Model_Index_Action_Refresh
     * @throws Exception
     */
    protected function _reindex($storeId, $changedIds = null)
    {
        Mage::getSingleton('catalogsearch/fulltext')
            ->rebuildIndex($storeId, $changedIds);

        return $this;
    }
}