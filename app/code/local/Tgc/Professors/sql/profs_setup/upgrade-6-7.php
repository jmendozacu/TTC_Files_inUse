<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$table = $conn->newTable($installer->getTable('profs/product'))
    ->addColumn('professor_id', Varien_Db_Ddl_Table::TYPE_INTEGER, array('unsigned' => true, 'nullable' => false))
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, array('unsigned' => true, 'nullable' => false))
    ->addIndex(
        $installer->getIdxName('profs/product', array('professor_id', 'product_id')),
        array('professor_id', 'product_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_PRIMARY)
    )
    ->addForeignKey(
        $installer->getFkName('profs/product', 'professor_id', 'profs/professor', 'professor_id'),
        'professor_id', $installer->getTable('profs/professor'), 'professor_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('profs/product', 'product_id', 'catalog/product', 'entity_id'),
        'product_id', $installer->getTable('catalog/product'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    );
$conn->createTable($table);

$installer->endSetup();