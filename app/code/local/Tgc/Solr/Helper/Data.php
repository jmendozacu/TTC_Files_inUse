<?php
/**
 * Solr search
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Solr
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Solr_Helper_Data extends Mage_Core_Helper_Data
{
    private $_isMinQueryLength;

    public function isSolr()
    {
        return !Mage::helper('enterprise_catalogsearch')->isFulltextOn();
    }

    public function isMinQueryLength()
    {
        if (isset($this->_isMinQueryLength)) {
            return $this->_isMinQueryLength;
        }

        $minQueryLength = intval(Mage::helper('catalogsearch')->getMinQueryLength());
        $thisQueryLength = intval(Mage::helper('core/string')->strlen(Mage::helper('catalogsearch')->getQueryText()));

        $this->_isMinQueryLength = $thisQueryLength >= $minQueryLength;

        return $this->_isMinQueryLength;
    }

    public function getQuery()
    {
        $queryText = Mage::helper('catalogsearch')->getQueryText();
        return $queryText;
    }

    public function getOnSaleOptionValue($customerGroupId = null, $websiteId = null)
    {
        if (is_null($customerGroupId)) {
            $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        }

        if (is_null($websiteId)) {
            $websiteId = Mage::app()->getStore()->getWebsiteId();
        }

        return 'on_sale_' . $customerGroupId . '_' . $websiteId;
    }
}
