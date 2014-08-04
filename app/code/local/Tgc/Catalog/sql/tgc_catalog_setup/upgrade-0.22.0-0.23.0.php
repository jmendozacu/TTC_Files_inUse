<?php
/**
 * Create attribute category title
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
    'category_title' => array(
        'type'                 => 'varchar',
        'label'                => 'Category Title',
        'input'                => 'text',
        'default'              => '',
        'position'             => 0,
        'global'               => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible'              => true,
        'required'             => false,
        'user_defined'         => true,
        'searchable'           => false,
        'filterable'           => false,
        'comparable'           => false,
        'visible_on_front'     => true,
        'unique'               => false,
        'group'                => 'General Information',
        'is_visible_on_front'  => true
    ),
);
$conn->beginTransaction();
try {
    foreach($categoryAttributes as $attributeCode => $attributeOptions) {
        $installer->addAttribute($entity, $attributeCode, $attributeOptions);
    }
    $conn->commit();
} catch (Exception $e) {
    $conn->rollBack();
    throw $e;
}

$installer->endSetup();
