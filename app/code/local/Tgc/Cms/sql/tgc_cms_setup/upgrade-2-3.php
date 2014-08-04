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
    DROP TABLE IF EXISTS `{$this->getTable('tgc_cms/partners')}`;
");

$table = $conn->newTable($installer->getTable('tgc_cms/partners'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn('alt_text', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Alt Text')
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
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
    ), 'Is Active')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Description')
    ->addColumn('image', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Image')
    ->addIndex($installer->getIdxName($installer->getTable('tgc_cms/partners'), array('sort_order')),
        array('sort_order'))
    ->setComment('CMS Partners Table');

$conn->createTable($table);

$installer->endSetup();
