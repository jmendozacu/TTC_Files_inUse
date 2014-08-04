<?php
/**
 * Bazaarvoice
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Bazaarvoice
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Bazaarvoice_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$entity = Mage_Catalog_Model_Product::ENTITY;

$data = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'decimal',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'text',
    'label' => 'Rating',
    'frontend_class' => 'validate-number',
    'source' => NULL,
    'required' => '0',
    'user_defined' => '0',
    'default' => NULL,
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '1',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '1',
    'used_in_product_listing' => '1',
    'used_for_sort_by' => '1',
    'is_configurable' => '0',
    'apply_to' => NULL,
    'visible_in_advanced_search' => '1',
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
$this->addAttribute($entity, 'bazaarvoice_rating', $data);

//add it to attribute set
$attr        = $installer->getAttribute($entity, 'bazaarvoice_rating');
$attrId      = $attr['attribute_id'];
$attrSetName = 'Courses';
$attrSet     = $installer->getAttributeSet($entity, $attrSetName);
$attrGroup   = $installer->getAttributeGroup($entity, $attrSet['attribute_set_id'], 'General');
$attrGroupId = $attrGroup['attribute_group_id'];
$installer->addAttributeToSet($entity, $attrSet['attribute_set_id'], $attrGroupId, $attrId);

$installer->endSetup();
