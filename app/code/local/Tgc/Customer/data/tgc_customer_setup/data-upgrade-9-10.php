<?php
/**
 * @category    TGC
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance
 * @author      Guidance Magento Team <magento@guidance.com>
 */

/* @var $installer Tgc_Customer_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->setConfigData('persistent/options/remember_enabled', 0);
$installer->setConfigData('persistent/options/remember_default', 0);

$installer->endSetup();
