<?php
/**
 * Install product attributes
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Catalog_Model_Resource_Setup */

//The primary_subject attribute was deleted, then it was decided we needed it,  the code below adds the primarySubject field back.

$installer = $this;

$entity = Mage_Catalog_Model_Product::ENTITY;
$attrCodes = array();
$attrIds = array();


//primary subject
$primarySubject = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'varchar',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'text',
    'label' => 'Primary Subject',
    'frontend_class' => NULL,
    'source' => NULL,
    'required' => '0',
    'user_defined' => '1',
    'default' => NULL,
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '0',
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
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
        ),
    ),
);
$this->addAttribute($entity, 'primary_subject', $primarySubject);
$attrCodes[] = 'primary_subject';


foreach ($attrCodes as $code) {
    $attr = $installer->getAttribute($entity, $code);
    $attrIds[] = $attr['attribute_id'];
}

$attrSetName = 'Courses';
$attrSet = $installer->getAttributeSet($entity, $attrSetName);
if (!isset($attrSet['attribute_set_id'])) {
    $defaultSetId =  Mage::getModel('catalog/product')
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
