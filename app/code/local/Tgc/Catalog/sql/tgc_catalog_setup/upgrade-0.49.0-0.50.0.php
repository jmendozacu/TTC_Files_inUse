<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Catalog_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

/** @var $client Enterprise_Mview_Model_Client */
$client = Mage::getModel('enterprise_mview/client');
$client->init('tgc_catalog_product_alltypes');
$client->getMetadata()
    ->setKeyColumn('entity_id')
    ->setViewName('tgc_catalog_product_alltypes_view')
    ->setGroupCode('tgc_catalog_product_alltypes')
    ->setStatus(Enterprise_Mview_Model_Metadata::STATUS_INVALID)
    ->save();

// Create product flat changelog
$client->execute('enterprise_index/action_index_changelog_create');

$subscriptions = array(
    $this->getTable(array('catalog/product', 'decimal'))  => 'entity_id',
    $this->getTable(array('catalog/product', 'int'))      => 'entity_id',
    $this->getTable(array('catalog/product', 'text'))     => 'entity_id',
    $this->getTable(array('catalog/product', 'varchar'))  => 'entity_id',
    $this->getTable(array('catalog/product', 'datetime')) => 'entity_id',
);

//exclude on_sale and all_types attributes from triggering changes
$onSaleAttributeId = $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'on_sale');
$allTypesAttributeId = $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'all_types');

$triggerInsert =
    "CASE (".$installer->getConnection()->quoteInto('NEW.attribute_id IN (?)', array($onSaleAttributeId, $allTypesAttributeId)).")\n".
    "WHEN FALSE THEN BEGIN ".
        "INSERT IGNORE INTO ".$installer->getTable('tgc_catalog_product_alltypes_cl')." (entity_id) VALUES (NEW.entity_id); ".
    "END; ELSE BEGIN END; END CASE;";

$triggerDelete =
    "CASE (".$installer->getConnection()->quoteInto('OLD.attribute_id IN (?)', array($onSaleAttributeId, $allTypesAttributeId)).")\n".
    "WHEN FALSE THEN BEGIN ".
        "INSERT IGNORE INTO ".$installer->getTable('tgc_catalog_product_alltypes_cl')." (entity_id) VALUES (OLD.entity_id); ".
    "END; ELSE BEGIN END; END CASE;";

foreach ($subscriptions as $targetTable => $targetColumn) {
    $arguments = array(
        'target_table'  => $targetTable,
        'target_column' => $targetColumn
    );

    $arguments['custom_triggers'] = array(
        Mage::getModel('tgc_mview/customTrigger')
            ->setEventName(Magento_Db_Sql_Trigger::SQL_EVENT_UPDATE)
            ->setTriggerBody($triggerInsert),
        Mage::getModel('tgc_mview/customTrigger')
            ->setEventName(Magento_Db_Sql_Trigger::SQL_EVENT_INSERT)
            ->setTriggerBody($triggerInsert),
        Mage::getModel('tgc_mview/customTrigger')
            ->setEventName(Magento_Db_Sql_Trigger::SQL_EVENT_DELETE)
            ->setTriggerBody($triggerDelete),
    );

    $client->execute('enterprise_mview/action_changelog_subscription_create', $arguments);
}

$installer->endSetup();