<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Boutique_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$installer->run("
    DROP TABLE IF EXISTS `tgc_promo_pages`;
");

$block = Mage::getModel('cms/block')->load('promo-page-left-block', 'identifier');
if ($block->getId()) {
    $block->delete();
}

$installer->run("
    DELETE FROM `core_resource` WHERE `core_resource`.`code` = 'tgc_promo_setup';
    DELETE FROM `core_resource` WHERE `core_resource`.`code` = 'tgc_sale_setup';
");

$deleteCats = array(
    'Sale',
    'Special Sale 70% Off',
    'Best Selling',
    'Courses Under $40',
    'Clearance',
    'Special Set Offers',
);

$cats = Mage::getModel('catalog/category')
    ->getCollection()
    ->addAttributeToFilter('name', array('in' => $deleteCats));
foreach ($cats as $cat) {
    try {
        $cat->delete();
    } catch (Exception $e) {
        Mage::logException($e);
    }
}

$installer->run("
    DROP TABLE IF EXISTS `{$installer->getTable('tgc_boutique/boutiquePages')}`;
");

$table = $conn->newTable($installer->getTable('tgc_boutique/boutiquePages'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn('page_title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Page Title')
    ->addColumn('meta_keywords', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        'nullable'  => true,
    ), 'Page Meta Keywords')
        ->addColumn('meta_description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        'nullable'  => true,
    ), 'Page Meta Description')
    ->addColumn('url_key', Varien_Db_Ddl_Table::TYPE_TEXT, 100, array(
        'nullable'  => true,
        'default'   => null,
    ), 'Page Url Key')
    ->addColumn('header_block', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
    ), 'Header Block ID')
    ->addColumn('content_block', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
    ), 'Content Block ID')
    ->addColumn('footer_block', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
    ), 'Footer Block ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Store ID')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Sort Order')
    ->addIndex(
        $installer->getIdxName(
            $installer->getTable('tgc_boutique/boutiquePages'),
            array('url_key'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('url_key'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->addIndex($installer->getIdxName('tgc_boutique/boutiquePages', array('store_id')),
        array('store_id'))
    ->addIndex($installer->getIdxName('tgc_boutique/boutiquePages', array('sort_order')),
        array('sort_order'))
    ->addForeignKey($installer->getFkName('tgc_boutique/boutiquePages', 'header_block', 'cms/block', 'block_id'),
        'header_block', $installer->getTable('cms/block'), 'block_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('tgc_boutique/boutiquePages', 'content_block', 'cms/block', 'block_id'),
        'content_block', $installer->getTable('cms/block'), 'block_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('tgc_boutique/boutiquePages', 'footer_block', 'cms/block', 'block_id'),
        'footer_block', $installer->getTable('cms/block'), 'block_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Tgc Boutique Pages');

$conn->createTable($table);

$installer->run("
    DROP TABLE IF EXISTS `{$installer->getTable('tgc_boutique/boutique')}`;
");

$table = $conn->newTable($installer->getTable('tgc_boutique/boutique'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Boutique Name')
    ->addColumn('url_key', Varien_Db_Ddl_Table::TYPE_TEXT, 100, array(
        'nullable'  => true,
        'default'   => null,
    ), 'Page Url Key')
    ->addColumn('pages', Varien_Db_Ddl_Table::TYPE_TEXT, 100, array(
        'nullable'  => true,
        'default'   => null,
    ), 'Boutique Pages')
    ->addColumn('is_default', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'default'   => '1',
    ), 'Is Boutique Default')
    ->addIndex(
    $installer->getIdxName(
            $installer->getTable('tgc_boutique/boutique'),
            array('url_key'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('url_key'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Tgc Boutiques');

$conn->createTable($table);

$installer->endSetup();
