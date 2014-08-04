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

$installer->setConfigData('checkout/options/guest_checkout', 0);
$installer->setConfigData('checkout/options/customer_must_be_logged', 1);

$installer->endSetup();
