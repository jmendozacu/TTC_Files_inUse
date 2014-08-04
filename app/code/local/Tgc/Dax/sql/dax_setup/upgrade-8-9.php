<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->addColumn(
        $installer->getTable('salesrule/rule'),
        'is_imported',
        array(
            'type'     => Varien_Db_Ddl_Table::TYPE_SMALLINT,
            'comment'  => 'Is Rule Imported',
            'nullable' => false,
            'default'  => 0,
        )
    );

$installer->getConnection()
    ->addColumn(
        $installer->getTable('salesrule/rule'),
        'shipping_amount',
        array(
            'type'      => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'scale'     => '4',
            'precision' => '12',
            'unsigned'  => true,
            'nullable'  => true,
            'comment'   => 'Flat Shipping Amount',
        )
    );

$installer->getConnection()
    ->addColumn(
    $installer->getTable('salesrule/rule'),
        'shipping_type',
        array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 64,
            'nullable' => true,
            'comment'  => 'Shipping Type',
        )
    );

$installer->getConnection()
    ->addColumn(
    $installer->getTable('salesrule/rule'),
        'special_item',
        array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 64,
            'nullable' => true,
            'comment'  => 'Special Item',
        )
    );

$installer->getConnection()
    ->addColumn(
    $installer->getTable('salesrule/rule'),
        'special_item_type',
        array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'   => 64,
            'nullable' => true,
            'comment'  => 'Special Item Type',
        )
    );

$installer->endSetup();
