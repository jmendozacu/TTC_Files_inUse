<?php
/**
 * @category    TGC
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance
 * @author      Guidance Magento Team <magento@guidance.com>
 */

/* @var $installer Tgc_Customer_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();
$conn = $installer->getConnection();

$conn->addColumn(
    $installer->getTable('customer_entity'),
    'lock_expires',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_BIGINT,
        'nullable' => true,
        'comment'  => 'Account Expiration Lock Timestamp',
    )
);

$installer->addAttribute(
    'customer',
    'lock_expires',
    array(
        'type' => 'static',
        'label' => 'Account Expiration Lock Timestamp',
        'input' => 'text',
        'visible' => false,
        'required' => false,
        'default_value' => '',
        'adminhtml_only' => '1'
    )
);

$conn->addColumn(
    $installer->getTable('customer_entity'),
    'num_failures',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'nullable' => true,
        'comment'  => 'Number of Login Failures',
    )
);

$installer->addAttribute(
    'customer',
    'num_failures',
    array(
        'type' => 'static',
        'label' => 'Number of Login Failures',
        'input' => 'text',
        'visible' => false,
        'required' => false,
        'default_value' => '',
        'adminhtml_only' => '1'
    )
);

$installer->endSetup();
