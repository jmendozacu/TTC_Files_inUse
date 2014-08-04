<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('sales/order_payment');

$conn->addColumn($tableName, 'gateway_transaction_id', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => 60,
    'comment' => 'Transaction reference number from Chase Paymentech',
));
$conn->addColumn($tableName, 'resp_date_time', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => 14,
    'comment' => 'Date autorized by Chase Paymentech',
));
$conn->addColumn($tableName, 'resp_code', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => 4,
    'comment' => 'Chase Paymentech response code',
));
$conn->addColumn($tableName, 'authorization_code', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => 40,
    'comment' => 'Chase Paymentech autorization verification code',
));
$conn->addColumn($tableName, 'merchant_id', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => 10,
    'comment' => 'Chase Paymentech merchant ID from authorization',
));
$conn->addColumn($tableName, 'avs_resp_code', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => 1,
    'comment' => 'Chase Paymentech address validation code',
));

$installer->endSetup();
