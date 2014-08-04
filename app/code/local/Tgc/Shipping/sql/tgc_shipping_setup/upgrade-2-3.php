<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Shipping
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;

$installer->startSetup();

$installer->run("
    DROP TABLE IF EXISTS `{$this->getTable('tgc_shipping/flatRate')}`;
");

$table = $conn->newTable($installer->getTable('tgc_shipping/flatRate'))
    ->addColumn('link_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Link ID')
    ->addColumn(
        'customer_group_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        5,
        array('unsigned' => true, 'nullable' => false),
        'Customer Group ID'
    )
    ->addColumn(
        'website_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array('nullable' => false, 'unsigned' => true, 'default' => 0),
        'Website ID'
    )
    ->addColumn(
        'shipping_price',
        Varien_Db_Ddl_Table::TYPE_DECIMAL,
        '12,4',
        array('nullable' => false, 'default' => '0.0000'),
        'Shipping Price'
    )
    ->addIndex(
        $installer->getIdxName(
            $installer->getTable('tgc_shipping/flatRate'),
            array('customer_group_id', 'website_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('customer_group_id', 'website_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->addForeignKey(
        $installer->getFkName('tgc_shipping/flatRate', 'customer_group_id', 'customer/customer_group', 'customer_group_id'),
        'customer_group_id', $installer->getTable('customer/customer_group'), 'customer_group_id'
);

$conn->createTable($table);

$installer->endSetup();
