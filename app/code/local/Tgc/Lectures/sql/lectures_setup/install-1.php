<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();
$table = $installer->getConnection()
    ->newTable($installer->getTable('lectures/lectures'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), 'id')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'product id')
    ->addColumn('lecture_id', Varien_Db_Ddl_Table::TYPE_VARCHAR,
        255, array('nullable'  => false), 'lecture_id')
    ->addColumn('lecture_number', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned'  => true,
            'nullable'  => true,
            'default'   => null,
            'length'     => 20,
        ), 'lecture number')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_VARCHAR,
        255, array('nullable'  => true, 'default' => null), 'title')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT,
        null, array('nullable'  => true, 'default' => null), 'description')
    ->addColumn('duration', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => true,
        'default'   => true,
    ), 'duration');

$installer->getConnection()->createTable($table);
$installer->endSetup();
