<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();

/****************************************************************************************************************************/
//Adding a guidebook attribute so that admin users can upload a guidebook on the general tab.


$attributeCode = "guidebook";

$installer->removeAttribute(Mage_Catalog_Model_Product::ENTITY, $attributeCode);

$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY , // Entity the new attribute is supposed to be added to
    $attributeCode, // attribute code
    array( // Array containing all settings:
        "type"              => "varchar",
        "label"             => 'Guidebook',
        'default'           => '',
        "input"             => "file",
        'backend'           => 'Tgc_Lectures_Model_Product_Attribute_Backend_Pdf',
        "required"          => 0,
        "global"            => 1, //1 = global
        "user_defined"      => true,
        "group"             => "General",
        "used_in_product_listing" => 1,
        "apply_to"          => ''
    )
);

$installer->endSetup();
