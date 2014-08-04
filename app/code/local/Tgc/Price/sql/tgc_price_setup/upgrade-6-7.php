<?php
/**
 * Update ad code table to drop catalog code column
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

$conn->dropColumn($installer->getTable('tgc_price/adCode'), 'catalog_code');

$installer->endSetup();
