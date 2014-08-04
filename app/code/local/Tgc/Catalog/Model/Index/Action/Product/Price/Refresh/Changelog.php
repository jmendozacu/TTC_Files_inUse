<?php

/**
 * Refresh category flat index by changelog action.
 * We need to disable Price indexer, because we write all data into
 * price_index table directly after importing of prices.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Catalog_Model_Index_Action_Product_Price_Refresh_Changelog
    extends Enterprise_Catalog_Model_Index_Action_Product_Price_Refresh_Changelog
{
    /**
     * Refresh entities index
     *
     * @param array $changedIds
     * @return array Affected ids
     */
    protected function _reindex($changedIds = array())
    {
        $this->_emptyTable($this->_getIdxTable());
        $this->_prepareWebsiteDateTable();

        //Here we need to disable all other indexers for prices.

        return $changedIds;
    }
}
