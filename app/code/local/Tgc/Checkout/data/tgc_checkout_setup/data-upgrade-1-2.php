<?php
/**
 * User: mhidalgo
 * Date: 14/05/14
 * Time: 13:21
 */

/**
 * @var $installer Mage_Catalog_Model_Resource_Setup
 */
$installer = $this;

$installer->startSetup();

/** @var $config Mage_Core_Model_Config */
$config = new Mage_Core_Model_Config();

$config->saveConfig(Mage_Checkout_Helper_Data::XML_PATH_CUSTOMER_MUST_BE_LOGGED,"0","default",0);

$installer->endSetup();