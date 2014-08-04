<?php
/**
 * Change type of "partner" attribute
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Catalog_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$attributeId = $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'partner');
if ($attributeId) {
    $installer->getConnection()->delete(
        $installer->getAttributeTable(Mage_Catalog_Model_Product::ENTITY, $attributeId),
        $installer->getConnection()->quoteInto('attribute_id = ?', $attributeId)
    );
    $installer->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'partner', 'backend_type', 'int');
}

$installer->endSetup();
