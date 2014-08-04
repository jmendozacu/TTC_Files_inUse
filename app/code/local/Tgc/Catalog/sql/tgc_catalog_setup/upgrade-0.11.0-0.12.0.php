<?php
/**
 * Create attributes
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Catalog_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$entity = Mage_Catalog_Model_Category::ENTITY;
$categoryAttributes = array(
    'thumb' => array(
        'group'         => 'General Information',
        'input'         => 'image',
        'type'          => 'varchar',
        'label'         => 'Slider Image',
        'backend'       => 'catalog/category_attribute_backend_image',
        'visible'       => true,
        'required'      => false,
        'user_defined'  => true,
        'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    ),
);
$conn->beginTransaction();
try {
    $installer->removeAttribute($entity, 'thumb');
    foreach($categoryAttributes as $attributeCode => $attributeOptions) {
        $installer->addAttribute($entity, $attributeCode, $attributeOptions);
    }
    $conn->commit();
} catch (Exception $e) {
    $conn->rollBack();
    throw $e;
}

$installer->endSetup();
