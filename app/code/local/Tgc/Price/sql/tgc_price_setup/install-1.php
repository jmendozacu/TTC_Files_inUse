<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;

$installer->startSetup();

$conn->addColumn($installer->getTable('customer/customer_group'), 'catalog_code', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'length'    => null,
    'default'   => null,
    'comment'   => 'Catalog code from DAX',
));

$table = $conn->newTable($installer->getTable('tgc_price/adCode'))
    ->addColumn(
        'code', 
        Varien_Db_Ddl_Table::TYPE_INTEGER, 
        null, 
        array('primary'  => true, 'unsigned' => true, 'nullable' => false),
        'Ad code from DAX'
    )
    ->addColumn(
        'customer_group_id', 
        Varien_Db_Ddl_Table::TYPE_SMALLINT, 
        5, 
        array('nullable' => false, 'unsigned' => true),
        'Customer group ID'
    )
    ->addForeignKey(
        $installer->getFkName('tgc_price/adCode', 'customer_group_id', 'customer/customer_group', 'customer_group_id'), 
        'customer_group_id', $installer->getTable('customer/customer_group'), 'customer_group_id'
    );

$conn->createTable($table);

$installer->endSetup();