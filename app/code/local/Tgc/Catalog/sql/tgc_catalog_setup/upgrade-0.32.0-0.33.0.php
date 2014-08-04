<?php
/**
 * User: mhidalgo
 * Date: 27/03/14
 * Time: 11:09
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
    'input' => 'select',
    'type' => 'int',
    'source' => 'eav/entity_attribute_source_table',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'required' => false,
    'is_visible' => true,
    'is_searchable'    => false,
    'is_filterable'    => false,
    'unique'        => false,
    'is_comparable'    => false,
    'is_visible_on_front' => false,
    'is_html_allowed_on_front' => false,
    'user_defined'  => false,
    'is_configurable'   => true,
    'is_used_for_promo_rules'  => false,
    'is_visible_in_advanced_search'    => false,
    'used_in_product_listing'   => false,
    'used_for_sort_by'  => false,
    'is_filterable_in_search'  => false,
    'apply_to' => NULL
);

$this->addAttribute($entity, 'collection', $collection);
$attrCodes[] = 'collection';

foreach ($attrCodes as $code) {
    $attr = $installer->getAttribute($entity, $code);
    $installer->updateAttribute($entity,$attr['attribute_id'],$collection);
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