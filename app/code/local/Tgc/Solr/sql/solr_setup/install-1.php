<?php
/**
 * Solr search
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Solr
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Solr_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$entity = Mage_Catalog_Model_Product::ENTITY;

$data = array(
    'filterable_in_search' => '1',
    'is_filterable_in_search' => '1',
);
//media_format update
$this->updateAttribute($entity, 'media_format', $data);
//price update
$this->updateAttribute($entity, 'price', $data);

//drop and re-create course_type_code as a select so it can be used in layered search
$this->removeAttribute($entity, 'course_type_code');
$data = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'int',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'select',
    'label' => 'Course Type',
    'frontend_class' => NULL,
    'source' => 'eav/entity_attribute_source_table',
    'required' => '0',
    'user_defined' => '1',
    'default' => '',
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '1',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '1',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '1',
    'apply_to' => 'simple',
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '1',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
            0 => 'Set',
            1 => 'Course',
        ),
    ),
);
$this->addAttribute($entity, 'course_type_code', $data);

//add it to attribute set
$attr        = $installer->getAttribute($entity, 'course_type_code');
$attrId      = $attr['attribute_id'];
$attrSetName = 'Courses';
$attrSet     = $installer->getAttributeSet($entity, $attrSetName);
$attrGroup   = $installer->getAttributeGroup($entity, $attrSet['attribute_set_id'], 'General');
$attrGroupId = $attrGroup['attribute_group_id'];
$installer->addAttributeToSet($entity, $attrSet['attribute_set_id'], $attrGroupId, $attrId);

$installer->endSetup();
