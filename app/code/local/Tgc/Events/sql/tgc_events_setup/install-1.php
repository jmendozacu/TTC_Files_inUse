<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;

$installer->startSetup();

$installer->run("
    DROP TABLE IF EXISTS `{$installer->getTable('events_locations')}`;
");
$table = $conn->newTable($installer->getTable('events_locations'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn(
        'location',
        Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => false,
        ), 'Location')
    ->addColumn(
        'location_code',
        Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => false,
        ), 'Location Code')
    ->addColumn(
        'location_image',
        Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => true,
        ), 'Location Image')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Sort Order')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Status')
    ->setComment('Events Locations Table');

$conn->createTable($table);

$installer->endSetup();
