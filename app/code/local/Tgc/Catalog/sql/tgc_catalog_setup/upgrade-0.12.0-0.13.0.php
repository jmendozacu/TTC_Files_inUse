<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();

/****************************************************************************************************************************/
//Adding a partner attribute, used to display logos on the page.

$installer->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'primary_subject');

$installer->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'package_subject');

$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY , // Entity the new attribute is supposed to be added to
    'partner', // attribute code
    array( // Array containing all settings:
        "type"              => "varchar",
        "label"             => 'Partner',
        'default'           => '',
        "input"             => "select",
        "source"             => "tgc_catalog/entity_attribute_source_partners",
        "required"          => 0,
        'searchable' => 0,
        'filterable' => 0,
        'comparable' => 0,
        "global"            => true,
        "user_defined"      => false,
        "group"             => "General",
        "used_in_product_listing" => 1,
        'visible_on_front' => 1,
        'visible_in_advanced_search' => 0,
        "apply_to"          => '',
    )
);

$installer->endSetup();

