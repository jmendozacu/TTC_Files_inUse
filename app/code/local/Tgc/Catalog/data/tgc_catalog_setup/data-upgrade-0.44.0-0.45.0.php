<?php
/**
 * User: mhidalgo
 * Date: 09/04/14
 * Time: 14:50
 */


/**
 * @var $installer Mage_Catalog_Model_Resource_Setup
 */
$installer = $this;

$installer->startSetup();

// Deactivate Index On Save
$pCollection = Mage::getSingleton('index/indexer')->getProcessesCollection();
foreach ($pCollection as $process) {
    $process->setMode(Mage_Index_Model_Process::MODE_MANUAL)->save();
}

$productIdsSaved = array();
$websites = Mage::getModel('core/website')->getCollection();
/** @var $website Mage_Core_Model_Website */
foreach ($websites as $website) {
    $defaultStore = $website->getDefaultStore();

    /** @var $collection Tgc_Catalog_Model_Resource_Product_Collection */
    $collection = Mage::getModel('catalog/product')->getCollection()
        ->addWebsiteFilter($website)
        ->addStoreFilter($defaultStore)
        ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
        ->addAttributeToFilter('type_id', array("eq" => Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE));

    // To avoid Re-Save Products this apply filter to products IDs that has been saved:
    if (!empty($productIdsSaved)) {
        $collection->addIdFilter($productIdsSaved,true);
    }

    Mage::getSingleton('catalog/product_visibility')->addVisibleInSiteFilterToCollection($collection);

    foreach ($collection as $product) {
        $product->save();
        $productIdsSaved[] = $product->getId();
    }
}

// Activate Index On Save
foreach ($pCollection as $process) {
    $process->setMode(Mage_Index_Model_Process::MODE_REAL_TIME)->save();
    // Re-index
    $process->reindexEverything();
}

$installer->endSetup();