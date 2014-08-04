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
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();
$conn = $installer->getConnection();

//we'll move installation of these to here since I'm removing hero carousel module
$categoryAttributes = array(
    'hero_image' => array(
        'group' => 'General Information',
        'input'         => 'image',
        'type'          => 'varchar',
        'label'         => 'Hero Image',
        'backend'       => 'catalog/category_attribute_backend_image',
        'visible'       => true,
        'required'      => false,
        'user_defined'  => true,
        'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    ),
    'hero_color' => array(
        'group' => 'General Information',
        'type'          => 'varchar',
        'label'         => 'Hero Color',
        'input'         => 'text',
        'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible'       => true,
        'required'      => false,
        'user_defined'  => true,
        'default'       => ''
    )
);

$conn->beginTransaction();
try {
    foreach($categoryAttributes as $attributeCode => $attributeOptions) {
        $installer->addAttribute('catalog_category', $attributeCode, $attributeOptions);
    }
    $conn->commit();
} catch (Exception $e) {
    $conn->rollBack();
    throw $e;
}

//let's remove those products attributes if they were created
$entity = Mage_Catalog_Model_Product::ENTITY;
$installer->removeAttribute($entity, 'hero_headline');
$installer->removeAttribute($entity, 'hero_description');
$installer->removeAttribute($entity, 'hero_tab_text');

$installer->run("
    DROP TABLE IF EXISTS `{$this->getTable('tgc_cms/categoryHeroCarousel')}`;
");

$table = $conn->newTable($installer->getTable('tgc_cms/categoryHeroCarousel'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Description')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Sort Order')
    ->addColumn('store', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Store')
    ->addColumn('category_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Category ID')
    ->addColumn('tab_title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Tab Title')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
    ), 'Is Active')
    ->addColumn('user_type', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '2',
    ), 'User Type')
    ->addColumn('active_from', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable'  => true,
    ), 'Active From')
    ->addColumn('active_to', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
    ), 'Active To')
    ->addColumn('tab_description', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Tab Description')
    ->addIndex($installer->getIdxName($installer->getTable('tgc_cms/categoryHeroCarousel'), array('sort_order')),
        array('sort_order'))
    ->addIndex($installer->getIdxName($installer->getTable('tgc_cms/categoryHeroCarousel'), array('category_id')),
        array('category_id'))
    ->addIndex($installer->getIdxName($installer->getTable('tgc_cms/categoryHeroCarousel'), array('product_id')),
        array('store'))
    ->addIndex($installer->getIdxName('tgc_cms/categoryHeroCarousel', array('is_active')),
        array('is_active'))
    ->addForeignKey($installer->getFkName('tgc_cms/categoryHeroCarousel', 'category_id', 'catalog/category', 'entity_id'),
        'category_id', $installer->getTable('catalog/category'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('CMS Category Hero Carousel Table');

$conn->createTable($table);
