<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Dax_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$entity = Mage_Catalog_Model_Product::ENTITY;
$attr = $installer->getAttribute($entity, 'course_type_code');
$attrId = $attr['attribute_id'];

$defaultSetId = Mage::getModel('catalog/product')
    ->getResource()
    ->getEntityType()
    ->getDefaultAttributeSetId();
$attrSetName = 'Sets';
$attrSetSets = $installer->getAttributeSet($entity, $attrSetName);

$attrGroup = $installer->getAttributeGroup($entity, $attrSetSets['attribute_set_id'], 'General');
$attrGroupSetsId = ($attrGroup) ? $attrGroup['attribute_group_id']
    : $installer->getDefaultAttributeGroupId($entity, $attrSetSets['attribute_set_id']);

$installer->addAttributeToSet($entity, $attrSetSets['attribute_set_id'], $attrGroupSetsId, $attrId);

$installer->run("
    UPDATE `catalog_eav_attribute`
        SET apply_to = 'configurable'
        WHERE attribute_id = $attrId;
");

$installer->run("
    INSERT INTO `catalog_product_entity_varchar` (entity_type_id, attribute_id, store_id, entity_id, value)
        SELECT `cpe`.`entity_type_id`, `ea`.`attribute_id`, 0, `cpe`.`entity_id`, `eaov`.`value_id`
        FROM `catalog_product_entity` AS `cpe`
        LEFT JOIN `eav_attribute_set` AS `eas`
            ON (`eas`.`attribute_set_id` = `cpe`.`attribute_set_id` AND `eas`.`entity_type_id` = `cpe`.`entity_type_id`)
        LEFT JOIN `eav_attribute` AS `ea`
            ON (`ea`.`entity_type_id` = `cpe`.`entity_type_id`)
        LEFT JOIN `eav_attribute_option` AS `eao`
            ON (`eao`.`attribute_id` = `ea`.`attribute_id`)
        INNER JOIN `eav_attribute_option_value` AS `eaov`
            ON (`eaov`.`option_id` = `eao`.`option_id` AND `eaov`.`value` = 'Set')
        WHERE `eas`.`attribute_set_name` = 'Sets' AND `cpe`.`type_id` = 'configurable' AND `ea`.`attribute_id` = $attrId
    ON DUPLICATE KEY UPDATE value = `eaov`.`value_id`;
");

$installer->run("
    INSERT INTO `catalog_product_entity_varchar` (entity_type_id, attribute_id, store_id, entity_id, value)
        SELECT `cpe`.`entity_type_id`, `ea`.`attribute_id`, 0, `cpe`.`entity_id`, `eaov`.`value_id`
        FROM `catalog_product_entity` AS `cpe`
        LEFT JOIN `eav_attribute_set` AS `eas`
            ON (`eas`.`attribute_set_id` = `cpe`.`attribute_set_id` AND `eas`.`entity_type_id` = `cpe`.`entity_type_id`)
        LEFT JOIN `eav_attribute` AS `ea`
            ON (`ea`.`entity_type_id` = `cpe`.`entity_type_id`)
        LEFT JOIN `eav_attribute_option` AS `eao`
            ON (`eao`.`attribute_id` = `ea`.`attribute_id`)
        INNER JOIN `eav_attribute_option_value` AS `eaov`
            ON (`eaov`.`option_id` = `eao`.`option_id` AND `eaov`.`value` = 'Course')
        WHERE `eas`.`attribute_set_name` = 'Courses' AND `cpe`.`type_id` = 'configurable' AND `ea`.`attribute_id` = $attrId
    ON DUPLICATE KEY UPDATE value = `eaov`.`value_id`;
");

$installer->run("
    DELETE FROM `core_resource`
        WHERE code = 'dax_setup_set';
");

$installer->endSetup();
