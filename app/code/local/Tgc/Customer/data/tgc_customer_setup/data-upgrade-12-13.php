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

$installer->setConfigData('customer/account_share/scope', 0);  //zero corresponds to global.

$installer->endSetup();