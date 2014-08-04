<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/** @var $installer Tgc_Catalog_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * Add courses row into m_seo_url table
 */
$manaSeoSchemaCollection = Mage::helper('mana_db')->getResourceModel('mana_seo/schema/store_flat_collection');
$data = array();
foreach ($manaSeoSchemaCollection as $schema) {
    Mage::helper('mana_db')->getModel('mana_seo/url')
        ->setUrlKey('courses')
        ->setUniqueKey('courses')
        ->setType('root_category')
        ->setStatus('active')
        ->setIsPage(1)
        ->setIsParameter(0)
        ->setIsAttributeValue(0)
        ->setIsCategoryValue(0)
        ->setIncludeFilterName(0)
        ->setFinalIncludeFilterName(0)
        ->setFinalUrlKey('courses')
        ->setPosition(0)
        ->setSchemaId($schema->getId())
        ->save();
}

$installer->endSetup();
