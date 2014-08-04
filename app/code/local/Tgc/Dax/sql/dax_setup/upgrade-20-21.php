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

$conn->addColumn(
    $installer->getTable('sales/order'),
    'dax_order_id',
    "INT(10) UNSIGNED NOT NULL"
);

$conn->addKey(
    $this->getTable('sales/order'),
    'dax_order_id',
    'dax_order_id'
);

$installer->endSetup();
