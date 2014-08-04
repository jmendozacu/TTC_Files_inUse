<?php
/**
 * User: mhidalgo
 * Date: 13/03/14
 * Time: 09:13
 */

/**
 * @var $installer Mage_Catalog_Model_Resource_Setup
 */
$installer = $this;

$installer->startSetup();

$entity = Mage_Catalog_Model_Product::ENTITY;
$attrCodes = array();
$attrIds = array();
$collection = array(
    'label' => "Collection",
    'backend' => '',
    'frontend' => '',
    'class' => '',
    'default' => '0',
    'input' => 'boolean',
    'type' => 'int',
    'source' => 'eav/entity_attribute_source_table',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'required' => '0',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => NULL,
    'visible_in_advanced_search' => '0',
);

$this->addAttribute($entity, 'collection', $collection);
$attrCodes[] = 'collection';

foreach ($attrCodes as $code) {
    $attr = $installer->getAttribute($entity, $code);
    $attrIds[] = $attr['attribute_id'];
}

$attrSetName = 'Courses';
$attrSet = $installer->getAttributeSet($entity, $attrSetName);
if (!isset($attrSet['attribute_set_id'])) {
    $defaultSetId = Mage::getModel('catalog/product')
        ->getResource()
        ->getEntityType()
        ->getDefaultAttributeSetId();
    $installer->copyAttributeSetId($attrSetName, $defaultSetId);
    $attrSet = $installer->getAttributeSet($entity, $attrSetName);
}

$attrGroup = $installer->getAttributeGroup($entity, $attrSet['attribute_set_id'], 'General');
$attrGroupId = ($attrGroup)
    ? $attrGroup['attribute_group_id']
    : $installer->getDefaultAttributeGroupId($entity, $attrSetId);

foreach ($attrIds as $id) {
    $installer->addAttributeToSet($entity, $attrSet['attribute_set_id'], $attrGroupId, $id);
}

$installer->endSetup();