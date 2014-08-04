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

//drop rating and recreate as select
$installer->removeAttribute($entity, 'bazaarvoice_rating');
$data = array (
    'attribute_model' => NULL,
    'backend' => 'eav/entity_attribute_backend_array',
    'type' => 'varchar',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'multiselect',
    'label' => 'Avg. Customer Review',
    'frontend_class' => NULL,
    'source' => NULL,
    'required' => '0',
    'user_defined' => '1',
    'default' => '',
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '2',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '0',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '1',
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
            0 => '<span class="rating-4-star">4 Stars</span> & Up',
            1 => '<span class="rating-3-star">3 Stars</span> & Up',
            2 => '<span class="rating-2-star">2 Stars</span> & Up',
            3 => '<span class="rating-1-star">1 Star</span> & Up',
        ),
    ),
);
$installer->addAttribute($entity, 'average_rating', $data);

//add it to attribute set
$attr        = $installer->getAttribute($entity, 'average_rating');
$attrId      = $attr['attribute_id'];
$attrSetName = 'Courses';
$attrSet     = $installer->getAttributeSet($entity, $attrSetName);
$attrGroup   = $installer->getAttributeGroup($entity, $attrSet['attribute_set_id'], 'General');
$attrGroupId = $attrGroup['attribute_group_id'];
$installer->addAttributeToSet($entity, $attrSet['attribute_set_id'], $attrGroupId, $attrId);

$installer->endSetup();
