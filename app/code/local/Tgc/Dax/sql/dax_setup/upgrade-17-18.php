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
    'is_exported',
    "TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'"
);

$conn->addColumn(
    $installer->getTable('sales/order'),
    'dax_received',
    "TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'"
);

$conn->addKey(
    $this->getTable('sales/order'),
    'is_exported',
    'is_exported'
);

$conn->addKey(
    $this->getTable('sales/order'),
    'dax_received',
    'dax_received'
);

$installer->endSetup();
