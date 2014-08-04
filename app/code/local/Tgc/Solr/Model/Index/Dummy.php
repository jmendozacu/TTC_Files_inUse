<?php
/**
 * Dummy Solr indexer for old Magento indexes.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Solr
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Solr_Model_Index_Dummy extends Enterprise_Index_Model_Indexer_Dummy
{
    /**
     * Retrieve Indexer name
     *
     * @return string
     */
    public function getName()
    {
        return Mage::helper('tgc_catalog')->__('Solr Fulltext indexer');
    }

    /**
     * Retrieve Indexer description
     *
     * @return string
     */
    public function getDescription()
    {
        return Mage::helper('tgc_catalog')->__('Refreshes product data in Solr');
    }
}