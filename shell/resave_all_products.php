<?php

require_once 'abstract.php';

/**
 * Resaves all products.
 * Was needed for fixing a bug with "All Types" attribute
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Shell
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Guidance_Shell_ProductResave extends Mage_Shell_Abstract
{
    /**
     * Run script
     *
     */
    public function run()
    {
        ini_set('memory_limit', '2048M');
        if ($this->getArg('help')) {
             echo $this->usageHelp();
        } else {

            $productResource = Mage::getResourceModel('catalog/product');
            $db = $productResource->getWriteConnection();
            $select = $db->select()->from($productResource->getTable('catalog/product'), 'entity_id')
                ->forUpdate(true);
            $db->beginTransaction();
            try {
                $stmt = $db->query($select);
                while ($row = $stmt->fetch()) {
                    Mage::getModel('catalog/product')->load($row['entity_id'])
                        ->save();
                    echo 'Saved ID: '.$row['entity_id']."\n";
                }

                //fix url rewrites

                $emptyUrlKeys = $db->select()
                    ->from(array('e' => $productResource->getTable('catalog/product')), array('entity_id' => 'e.entity_id'))
                    ->joinLeft(
                        array('k' => $productResource->getTable('catalog_product_entity_url_key')),
                        'e.entity_id=k.entity_id',
                        array('rel_store_id' => 'k.store_id')
                    )
                    ->where('k.value IS NULL');

                $stmt = $db->query($emptyUrlKeys);
                while ($row = $stmt->fetch()) {
                    /* @var $productLoaded Mage_Catalog_Model_Product */
                    $productLoaded = Mage::getModel('catalog/product')
                        ->setStoreId($row['rel_store_id'])
                        ->load($row['entity_id']);

                    if ($productLoaded->getId()) {
                        $productLoaded->setUrlKey('');
                        $productLoaded->getResource()->getAttribute('url_key')->getBackend()->beforeSave($productLoaded);
                        $productLoaded->addAttributeUpdate('url_key', $productLoaded->getUrlKey(), $row['rel_store_id']);
                    }
                    echo 'Saved URL KEY: '.$row['entity_id']."\n";
                }
                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                echo (string)$e;
            }
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f resave_all_products.php -- [options]
  help                          This help

USAGE;
    }
}

$shell = new Guidance_Shell_ProductResave();
$shell->run();