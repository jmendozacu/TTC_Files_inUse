<?php
/**
 * User: mhidalgo
 * Date: 28/02/14
 * Time: 10:46
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()->newTable($installer->getTable('tgc_zmag/zmag'))
    ->addColumn('zmag_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'unsigned' => true,
        'nulleable' => false,
        'primary' => true,
        'identity' => true,
    ), 'Zmag ID')
    ->addColumn('publication_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 100, array(
        'nulleable' => false,
    ), 'Publication ID')
    ->addColumn('page_instructions', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'Page Instructions')
    ->addColumn('icon', Varien_Db_Ddl_Table::TYPE_VARCHAR, 150, array(
        'nullable' => false,
    ), 'Icon')
    ->addColumn('website_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'nullable' => false,
    ), 'Website ID')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'nullable' => false,
    ), 'Status');

$installer->getConnection()->createTable($table);

$installer->endSetup();