<?php
/**
 * Fulltext index model.
 * We need to override it for making our indexer by changelog working, when we enable Solr.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Solr
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Solr_Model_Index_Fulltext extends Enterprise_CatalogSearch_Model_Indexer_Fulltext
{
    /**
     * Return whether fulltext engine is on
     *
     * @return bool
     */
    protected function _isFulltextOn()
    {
        if (is_null($this->_fulltextOn)) {
            $this->_fulltextOn = Mage::helper('enterprise_catalogsearch')->isFulltextOn() ||
                Mage::helper('tgc_solr')->isSolr();
        }
        return $this->_fulltextOn;
    }
}