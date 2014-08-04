<?php
/**
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Setup
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->setConfigData('payment/paymentech/title', 'Credit Card (Saved)');
$installer->setConfigData('payment/paymentech/payment_profiles_enabled', 1);
$installer->setConfigData('payment/pbridge/profilestatus', 1);


$installer->endSetup();