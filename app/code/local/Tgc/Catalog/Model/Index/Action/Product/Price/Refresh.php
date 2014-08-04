<?php

/**
 * Full refresh price index
 * We need to disable Price indexer, because we write all data into
 * price_index table directly after importing of prices.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Catalog_Model_Index_Action_Product_Price_Refresh
    extends Enterprise_Catalog_Model_Index_Action_Product_Price_Refresh
{
    /**
     * Reindex all
     *
     * @return Tgc_Catalog_Model_Index_Action_Product_Price_Refresh
     */
    protected function _reindexAll()
    {
        $this->_useIdxTable(true);
        $this->_emptyTable($this->_getIdxTable());
        $this->_prepareWebsiteDateTable();

        return $this;
    }
}
