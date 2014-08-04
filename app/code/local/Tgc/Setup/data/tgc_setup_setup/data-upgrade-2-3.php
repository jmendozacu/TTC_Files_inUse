<?php
/**
 * Stores currency setup
 * 
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->setConfigData(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE, 'USD', $installer->getUsWebsite())
    ->setConfigData(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE, 'GBP', $installer->getUkWebsite())
    ->setConfigData(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE, 'AUD', $installer->getAuWebsite());

$installer->endSetup();