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

$conn = $installer->getConnection();

$conn->addIndex(
    $installer->getTable('customer_group'),
    $installer->getIdxName(
        $installer->getTable('customer_group'),
        array('catalog_code'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('catalog_code'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$conn->addColumn(
    $installer->getTable('customer_group'),
    'special_shipping_price',
    array(
        'type'    => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'comment' => 'Special Shipping Price',
        'scale'     => 4,
        'precision' => 12,
        'nullable'  => false,
        'default'   => '0',
    )
);

$conn->addColumn(
    $installer->getTable('customer_group'),
    'website_id',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        'comment'  => 'Website ID',
    )
);

$conn->addColumn(
    $installer->getTable('customer_group'),
    'start_date',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_DATETIME,
        'nullable'  => false,
        'default'   => '0000-00-00 00:00:00',
        'comment'  => 'Start Date',
    )
);

$conn->addColumn(
    $installer->getTable('customer_group'),
    'stop_date',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_DATETIME,
        'nullable'  => false,
        'default'   => '2099-12-31 00:00:00',
        'comment'  => 'Stop Date',
    )
);

$conn->addColumn(
    $installer->getTable('customer_group'),
    'name',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'   => 64,
        'nullable' => true,
        'comment'  => 'Name',
    )
);

$conn->addColumn(
    $installer->getTable('customer_group'),
    'description',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'   => 64,
        'nullable' => true,
        'comment'  => 'Description',
    )
);

$installer->endSetup();
