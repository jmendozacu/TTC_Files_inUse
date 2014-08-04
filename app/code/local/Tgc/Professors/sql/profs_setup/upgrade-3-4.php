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

$table = $conn->newTable($installer->getTable('profs/alma_mater'))
    ->addColumn('professor_id', Varien_Db_Ddl_Table::TYPE_INTEGER, array('unsigned' => true, 'nullable' => false))
    ->addColumn('institution_id', Varien_Db_Ddl_Table::TYPE_INTEGER, array('unsigned' => true, 'nullable' => false))
    ->addIndex(
        $installer->getIdxName('profs/alma_mater', array('professor_id', 'institution_id')),
        array('professor_id', 'institution_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_PRIMARY)
    )
    ->addForeignKey(
        $installer->getFkName('profs/alma_mater', 'professor_id', 'profs/professor', 'professor_id'),
        'professor_id', $installer->getTable('profs/professor'), 'professor_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('profs/alma_mater', 'institution_id', 'profs/institution', 'institution_id'),
        'institution_id', $installer->getTable('profs/institution'), 'institution_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    );
$conn->createTable($table);

$installer->endSetup();