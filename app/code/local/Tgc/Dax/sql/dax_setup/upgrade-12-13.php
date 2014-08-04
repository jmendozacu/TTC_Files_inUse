<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Dax_Model_Resource_Catalog_Setup */

$installer = $this;
$installer->startSetup();

$entity = $installer->getEntityTypeId(Mage_Catalog_Model_Product::ENTITY);

$eavAttributeConfig = Mage::getModel('eav/config');
$attribute = $eavAttributeConfig->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'meta_description');
$attributeId = $attribute->getId();

$installer->getConnection()->beginTransaction();

try {
    $installer->transferAttributeValues('varchar', 'text',
    $attributeId,
    $installer->getTable('catalog_product_entity'),
    $entity
);

    $installer->getConnection()->commit();
} catch (Exception $e) {
    $installer->getConnection()->rollBack();
    throw $e;
}

    $installer->endSetup();