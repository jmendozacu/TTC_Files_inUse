<?php
/**
 * Tgc Catalog
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Catalog_Model_Observer
{
    protected $_acceptableRequests = array(
       'admin/catalog_product/save',
       'admin/catalog_product_action_attribute/save',
       'admin/catalog_product/massStatus',
    );

    protected $_pageRequested;

    public function __construct()
    {
        $request = Mage::app()->getRequest();
        $this->_pageRequested = $request->getModulename() . DS . $request->getControllername() . DS . $request->getActionname();
    }

    public function updateHasAdminChanged(Varien_Event_Observer $observer)
    {
        if(in_array($this->_pageRequested, $this->_acceptableRequests, true)) {
            $observer->getProduct()->setHasAdminChanged(1);
        }
    }

    public function updateBatchSaveHasAdminChanged(Varien_Event_Observer $observer)
    {
        if(in_array($this->_pageRequested, $this->_acceptableRequests, true)) {
            $attributesData = $observer->getAttributesData();
            $attributesData['has_admin_changed'] = 1;
            $observer->getEvent()->setAttributesData($attributesData);
        }
    }

    /**
     * Process shell reindex "all_types" product attribute refresh event
     *
     * @param Varien_Event_Observer $observer
     * @return Enterprise_Mview_Model_Client
     */
    public function processShellProductReindexEvent(Varien_Event_Observer $observer)
    {
        $client = $this->_getClient(
            Mage::helper('enterprise_index')->getIndexerConfigValue('tgc_catalog_product_alltypes', 'index_table')
        );
        return $client->execute('tgc_catalog/index_action_product_allTypes_refresh');
    }

    /**
     * Get Enterprise indexer client
     *
     * @param string $metadataTableName
     * @return Enterprise_Mview_Model_Client
     */
    protected function _getClient($metadataTableName)
    {
        $client = Mage::getModel('enterprise_mview/client');
        $client->init($metadataTableName);
        return $client;
    }
}