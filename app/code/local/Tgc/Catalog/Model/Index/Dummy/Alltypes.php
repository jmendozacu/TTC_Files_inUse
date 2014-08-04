<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Catalog_Model_Index_Dummy_Alltypes extends Enterprise_Index_Model_Indexer_Dummy
{
    /**
     * Retrieve Indexer name
     *
     * @return string
     */
    public function getName()
    {
        return Mage::helper('tgc_catalog')->__('Product "All Types" attribute value indexer');
    }

    /**
     * Retrieve Indexer description
     *
     * @return string
     */
    public function getDescription()
    {
        return Mage::helper('tgc_catalog')->__('Refreshes product "All Types" attribute value');
    }
}