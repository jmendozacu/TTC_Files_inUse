<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Catalog_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$entity = Mage_Catalog_Model_Product::ENTITY;

$installer->updateAttribute($entity, 'attribute_set_defined', 'is_global', Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE);
$installer->updateAttribute($entity, 'on_sale', 'is_global', Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE);
$installer->updateAttribute($entity, 'all_types', 'is_global', Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE);

$installer->endSetup();