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

//creates the attribute has_admin_changed so we can tell during an import, if a record has been changed.

$installer = $this;

$entity = Mage_Catalog_Model_Product::ENTITY;
$attrCodes = array();
$attrIds = array();

//primary subject
$hasAdminChanged = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'varchar',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'text',
    'label' => 'Has admin changed',
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
    'visible_on_front' => '0',
    'is_html_allowed_on_front' => '0',
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
    'search_weight' => '0',
    'option' =>
    array (
        'values' =>
        array (
        ),
    ),
);

$this->addAttribute($entity, 'has_admin_changed', $hasAdminChanged);


