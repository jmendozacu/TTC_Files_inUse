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

$installer->setConfigData('sales/gift_options/allow_order', 1);
$installer->setConfigData('sales/gift_options/wrapping_allow_order', 0);

$installer->setConfigData('sales/gift_options/allow_items', 0);
$installer->setConfigData('sales/gift_options/wrapping_allow_items', 0);


$installer->endSetup();