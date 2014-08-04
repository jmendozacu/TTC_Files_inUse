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

$installer->run("
    DROP TABLE IF EXISTS `{$this->getTable('tgc_cms/heroCarousel')}`;
");

$table = $conn->newTable($installer->getTable('tgc_cms/heroCarousel'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Description')
    ->addColumn('mobile_description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Mobile Description')
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
    ->addColumn('tab_title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Tab Title')
    ->addColumn('tab_description', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Tab Description')
    ->addIndex($installer->getIdxName($installer->getTable('tgc_cms/heroCarousel'), array('sort_order')),
        array('sort_order'))
    ->setComment('CMS Hero Carousel Table');

$conn->createTable($table);

$installer->run("
    DROP TABLE IF EXISTS `{$this->getTable('tgc_cms/quotes')}`;
");

$table = $conn->newTable($installer->getTable('tgc_cms/quotes'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn('quote', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Quote')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Sort Order')
    ->addColumn('source', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Quote Source')
    ->addColumn('additional', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Additional Info')
    ->addIndex($installer->getIdxName($installer->getTable('tgc_cms/quotes'), array('sort_order')),
        array('sort_order'))
    ->setComment('CMS Quotes Table');

$conn->createTable($table);

$installer->run("
    DROP TABLE IF EXISTS `{$this->getTable('tgc_cms/bestSellers')}`;
");

$table = $conn->newTable($installer->getTable('tgc_cms/bestSellers'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn('course_id', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Course ID')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Sort Order')
    ->addIndex($installer->getIdxName($installer->getTable('tgc_cms/bestSellers'), array('sort_order')),
        array('sort_order'))
    ->setComment('CMS Best Sellers Table');

$conn->createTable($table);

$installer->endSetup();
