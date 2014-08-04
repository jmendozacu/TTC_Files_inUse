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
    DROP TABLE IF EXISTS `{$this->getTable('tgc_cms/categoryHeroCarousel')}`;
");

$table = $conn->newTable($installer->getTable('tgc_cms/categoryHeroCarousel'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn('category_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Category ID')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Sort Order')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'default'   => 1,
            'nullable'  => false
    ), 'Is Active')
    ->addIndex($installer->getIdxName($installer->getTable('tgc_cms/categoryHeroCarousel'), array('sort_order')),
        array('sort_order'))
    ->setComment('CMS Category Hero Carousel Table');

$conn->createTable($table);