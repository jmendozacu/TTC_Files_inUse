<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_DigitalLibrary_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();
$conn = $installer->getConnection();

$installer->run("
    DROP TABLE IF EXISTS `{$installer->getTable('tgc_dl/mergeAccounts')}`;
");

$table = $conn->newTable($installer->getTable('tgc_dl/mergeAccounts'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn(
        'dax_customer_id', Varien_Db_Ddl_Table::TYPE_TEXT, 25, array(
        'default'  => '',
    ), 'DAX Customer ID')
    ->addColumn(
        'mergeto_dax_customer_id', Varien_Db_Ddl_Table::TYPE_TEXT, 25, array(
        'default'  => '',
    ), 'Merge To DAX Customer ID')
    ->addIndex(
        $installer->getIdxName(
            $installer->getTable('tgc_dl/mergeAccounts'),
            array('dax_customer_id', 'mergeto_dax_customer_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('dax_customer_id', 'mergeto_dax_customer_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->addIndex($installer->getIdxName('tgc_dl/mergeAccounts', array('dax_customer_id')),
        array('dax_customer_id'))
    ->addIndex($installer->getIdxName('tgc_dl/mergeAccounts', array('mergeto_dax_customer_id')),
        array('mergeto_dax_customer_id'))
    ->addForeignKey(
        $installer->getFkName(
            'tgc_dl/mergeAccounts',
            'dax_customer_id',
            'customer/entity',
            'dax_customer_id'
        ),
        'dax_customer_id', $installer->getTable('customer/entity'), 'dax_customer_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName(
            'tgc_dl/mergeAccounts',
            'mergeto_dax_customer_id',
            'customer/entity',
            'dax_customer_id'
        ),
        'mergeto_dax_customer_id', $installer->getTable('customer/entity'), 'dax_customer_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Digital Library Merged Customer Accounts');

$conn->createTable($table);

$installer->endSetup();
