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
$conn = $installer->getConnection();

$entity = Mage_Catalog_Model_Product::ENTITY;
$attr   = $installer->getAttribute($entity, 'average_rating');
$attrId = $attr['attribute_id'];
$installer->removeAttribute($entity, 'average_rating');

$sql = sprintf('DELETE FROM `eav_attribute_option` WHERE `attribute_id` = (%s)', $attrId);
$conn->query($sql);

$values = array(
    Tgc_Bazaarvoice_Helper_Data::OPTION_1_STAR,
    Tgc_Bazaarvoice_Helper_Data::OPTION_2_STAR,
    Tgc_Bazaarvoice_Helper_Data::OPTION_3_STAR,
    Tgc_Bazaarvoice_Helper_Data::OPTION_4_STAR,
    Tgc_Bazaarvoice_Helper_Data::OPTION_5_STAR,
);

$sql = sprintf('DELETE FROM `eav_attribute_option_value` WHERE `value` IN (%s)', join(',', array_map(array($conn, 'quote'), $values)));
$conn->query($sql);

$data = array (
    'attribute_model' => NULL,
    'backend' => 'eav/entity_attribute_backend_array',
    'type' => 'varchar',
    'table' => NULL,
    'frontend' => NULL,
    'group' => 'General',
    'input' => 'multiselect',
    'label' => 'All Ratings',
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
    'filterable' => '1',
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
            0 => Tgc_Bazaarvoice_Helper_Data::OPTION_5_STAR,
            1 => Tgc_Bazaarvoice_Helper_Data::OPTION_4_STAR,
            2 => Tgc_Bazaarvoice_Helper_Data::OPTION_3_STAR,
            3 => Tgc_Bazaarvoice_Helper_Data::OPTION_2_STAR,
            4 => Tgc_Bazaarvoice_Helper_Data::OPTION_1_STAR,
        ),
    ),
);

$installer->addAttribute($entity, 'average_rating', $data);

$installer->endSetup();
