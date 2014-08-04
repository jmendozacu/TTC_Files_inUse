<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

/** @var $installer Tgc_Datamart_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * Add courses row into m_seo_url table
 */
$manaSeoSchemaCollection = Mage::helper('mana_db')->getResourceModel('mana_seo/schema/store_flat_collection');
$data = array();
foreach ($manaSeoSchemaCollection as $schema) {
    Mage::helper('mana_db')->getModel('mana_seo/url')
        ->setUrlKey('tgc/courses/specialoffer')
        ->setUniqueKey('tgc/courses/specialoffer')
        ->setType('email_landing')
        ->setStatus('active')
        ->setIsPage(1)
        ->setIsParameter(0)
        ->setIsAttributeValue(0)
        ->setIsCategoryValue(0)
        ->setIncludeFilterName(0)
        ->setFinalIncludeFilterName(0)
        ->setFinalUrlKey('tgc/courses/specialoffer')
        ->setPosition(0)
        ->setSchemaId($schema->getId())
        ->save();
}

$installer->endSetup();
