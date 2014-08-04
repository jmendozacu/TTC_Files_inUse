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
    DROP TABLE IF EXISTS `{$installer->getTable('events_types')}`;
");
$table = $conn->newTable($installer->getTable('events_types'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn(
        'type',
        Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => false,
        ), 'Type')
    ->addColumn(
        'type_icon',
        Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => false,
        ), 'Type Icon')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Sort Order')
    ->setComment('Events Types Table');

$conn->createTable($table);

$installer->endSetup();
