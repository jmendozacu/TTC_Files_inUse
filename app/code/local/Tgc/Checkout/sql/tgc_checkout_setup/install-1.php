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
    DROP TABLE IF EXISTS `{$installer->getTable('tgc_checkout_cart_migrate')}`;
");
$table = $conn->newTable($installer->getTable('tgc_checkout_cart_migrate'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn(
        'web_user_id',
        Varien_Db_Ddl_Table::TYPE_TEXT, 100, array(
            'nullable' => false,
        ), 'Web User Id')
    ->addColumn(
        'dax_customer_id',
        Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => false,
        ), 'Dax Customer Id')
    ->addColumn(
        'adcode',
        Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => true,
        ), 'Ad code')
    ->addColumn(
        'sku',
        Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
            'nullable' => true,
        ), 'Sku')
    ->addColumn('quantity', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Quantity')
    ->addColumn('is_claimed', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is Claimed Flag')
    ->addColumn(
        'claim_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 32, array(
            'nullable' => true,
        ), 'Claim Date')
    ->addIndex($installer->getIdxName('tgc_checkout_cart_migrate', array('web_user_id')),
        array('web_user_id'))
    ->setComment('Cart Migrate Table');

$conn->createTable($table);

$installer->endSetup();
