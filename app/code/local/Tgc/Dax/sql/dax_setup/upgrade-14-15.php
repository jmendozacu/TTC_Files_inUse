<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Dax_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$installer->run("
    DROP TABLE IF EXISTS `{$this->getTable('tgc_dax/emailUnsubscribe')}`;
");

$table = $conn->newTable($installer->getTable('tgc_dax/emailUnsubscribe'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn('web_key', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'unsigned' => true,
    ), 'Web Key')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'unsigned' => true,
    ), 'Customer ID')
    ->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Email Address')
    ->addColumn('unsubscribe_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
    ), 'Unsubscribe Date')
    ->addColumn('email_campaign', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Email Campaign')
    ->addColumn('is_exported', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is Exported To Dax')
    ->setComment('Dax Email Unsubscribe Page Table');

$conn->createTable($table);

$installer->endSetup();
