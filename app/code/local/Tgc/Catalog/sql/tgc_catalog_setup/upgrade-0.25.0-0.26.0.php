<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
$installer = $this;
$installer->startSetup();

$entity = Mage_Catalog_Model_Product::ENTITY;
$attributeName = "best_selling";

//media format
$bestsellingProduct = array(
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'int',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'select',
    'label' => 'Best Selling',
    'frontend_class' => NULL,
    'source' => 'eav/entity_attribute_source_boolean',
    'required' => '0',
    'user_defined' => '1',
    'default' => '0',
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '1',
    'group' => 'General',
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
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
);

$this->addAttribute($entity, $attributeName, $bestsellingProduct);

$installer->endSetup();