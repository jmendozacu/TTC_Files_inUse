<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Cms_Model_Resource_Setup */
$installer  = $this;

$attrCodes = array(
    Tgc_Cms_Block_BestSellers::GUEST_ATTRIBUTE,
    Tgc_Cms_Block_BestSellers::AUTHENTICATED_ATTRIBUTE,
);

$attrIds = array();
foreach ($attrCodes as $code) {
    $attr = $installer->getAttribute(Mage_Catalog_Model_Product::ENTITY, $code);
    $attrIds[] = $attr['attribute_id'];
}

foreach ($attrIds as $id) {
    $installer->run("
        UPDATE `eav_attribute`
            SET backend_type = 'decimal'
            WHERE attribute_id = $id;
    ");

    $installer->run("
        INSERT INTO `catalog_product_entity_decimal` (entity_type_id, attribute_id, store_id, entity_id, value)
            SELECT entity_type_id, attribute_id, store_id, entity_id, value
            FROM `catalog_product_entity_varchar`
            WHERE attribute_id = $id;
    ");

    $installer->run("
        DELETE FROM `catalog_product_entity_varchar`
            WHERE attribute_id = $id;
    ");
}

