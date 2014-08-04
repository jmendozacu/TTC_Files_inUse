<?php
/**
 * @category    Tgc
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
    'adcode',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'unsigned' => true,
        'nullable' => false,
        'default'  => '16281',
        'comment'  => 'Adcode',
    )
);

$conn->addForeignKey(
    $installer->getFkName('customer_entity', 'adcode', 'tgc_price/adCode', 'code'),
    $installer->getTable('customer_entity'),
    'adcode',
    $installer->getTable('tgc_price/adCode'),
    'code'
);

$installer->addAttribute(
    'customer',
    'adcode',
    array(
        'type'           => 'static',
        'label'          => 'Adcode',
        'input'          => 'text',
        'visible'        => false,
        'required'       => false,
        'default_value'  => '16281',
        'adminhtml_only' => '1'
    )
);

$conn->addColumn(
    $installer->getTable('customer_entity'),
    'adcode_expires',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_BIGINT,
        'nullable' => true,
        'comment'  => 'Adcode Expiration Timestamp',
    )
);

$installer->addAttribute(
    'customer',
    'adcode_expires',
    array(
        'type'           => 'static',
        'label'          => 'Adcode Expiration Timestamp',
        'input'          => 'text',
        'visible'        => false,
        'required'       => false,
        'default_value'  => null,
        'adminhtml_only' => '1'
    )
);

$installer->endSetup();
