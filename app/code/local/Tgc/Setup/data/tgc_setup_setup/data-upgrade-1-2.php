<?php
/**
 * Price mode setup
 * 
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->setConfigData(Mage_Core_Model_Store::XML_PATH_PRICE_SCOPE, Mage_Core_Model_Store::PRICE_SCOPE_WEBSITE);

$installer->endSetup();