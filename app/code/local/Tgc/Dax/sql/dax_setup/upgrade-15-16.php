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

$installer->getConnection()->dropColumn($installer->getTable('tgc_dax/emailUnsubscribe'), 'is_exported');
$installer->getConnection()->addColumn(
    $installer->getTable('tgc_dax/emailUnsubscribe'),
    'is_archived',
    "TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'");

$installer->endSetup();
