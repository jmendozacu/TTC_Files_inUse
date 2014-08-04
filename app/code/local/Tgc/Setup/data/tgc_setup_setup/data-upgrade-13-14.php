<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$installer->setConfigData('payment/paymentech/payment_action', Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE);
$installer->setConfigData('payment/paymentech/authorize_close_transaction', 1);

$installer->endSetup();
