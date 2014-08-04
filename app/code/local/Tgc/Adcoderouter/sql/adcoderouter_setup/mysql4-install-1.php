<?php

$installer = $this;

$installer->startSetup();


$table = $installer->getConnection()
    ->newTable($installer->getTable('adcoderouter/redirects'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'unique id')
    ->addColumn('search_expression', Varien_Db_Ddl_Table::TYPE_VARCHAR,
        255, array('nullable'  => false), 'Search Expression')
    ->addColumn('start_date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array('nullable'  => false,), 'Start Date')
    ->addColumn('end_date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array('nullable'  => false,), 'End Date')
    ->addColumn('ad_code', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'ad code')
    ->addColumn('course_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'course id')
    ->addColumn('professor_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'professor id')
    ->addColumn('category_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'category id')
    ->addColumn('cms_page_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'cms_page_id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'store id')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_VARCHAR,
        255, array('nullable'  => false), 'Description');

$installer->getConnection()->createTable($table);

$installer->endSetup();