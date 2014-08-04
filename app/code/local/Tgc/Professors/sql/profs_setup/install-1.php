<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$professorTable = $conn->newTable($installer->getTable('profs/professor'))
    ->addColumn('professor_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned' => true, 'identity' => true, 'primary' => true, 'nullable' => false), 'Identity')
    ->addColumn('first_name', Varien_Db_Ddl_Table::TYPE_TEXT, 128, array('nullable' => false), 'First name')
    ->addColumn('last_name', Varien_Db_Ddl_Table::TYPE_TEXT, 128, array('nullable' => false), 'Last name')
    ->addColumn('qual', Varien_Db_Ddl_Table::TYPE_TEXT, 256, array(), 'Qualification')
    ->addColumn('bio', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(), 'Biography')
    ->addColumn('rank', Varien_Db_Ddl_Table::TYPE_INTEGER, null)
    ->addColumn('quote', Varien_Db_Ddl_Table::TYPE_TEXT)
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 64)
    ->addColumn('category_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned' => true), 'Category ID')
    ->addForeignKey(
        $installer->getFkName('profs/professor', 'category_id', 'catalog/category', 'entity_id'),
        'category_id',
        $installer->getTable('catalog/category'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    );

$conn->createTable($professorTable);

$installer->endSetup();