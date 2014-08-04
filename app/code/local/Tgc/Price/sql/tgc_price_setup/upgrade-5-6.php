<?php
/**
 * Update ad code table
 * 
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Price
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$conn->addColumn($installer->getTable('tgc_price/adCode'), 'catalog_code', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'length'    => null,
    'default'   => null,
    'comment'   => 'Catalog Code',
));

$conn->addColumn($installer->getTable('tgc_price/adCode'), 'name', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => null,
    'default'   => null,
    'comment'   => 'Name',
));

$conn->addColumn($installer->getTable('tgc_price/adCode'), 'description', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => null,
    'default'   => null,
    'comment'   => 'Description',
));

$conn->addColumn($installer->getTable('tgc_price/adCode'), 'active_flag', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => null,
    'default'   => null,
    'comment'   => 'Active Flag',
));

$installer->endSetup();
