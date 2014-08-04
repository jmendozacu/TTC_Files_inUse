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

$installer->setConfigData(Mage_Core_Model_Cookie::XML_PATH_COOKIE_LIFETIME, 5184000); //60 days
$installer->setConfigData(Tgc_Customer_Model_ActiveSession::XML_PATH_ACTIVE_SESSION_LIFETIME, 900); //15 min
$installer->setConfigData('persistent/options/enabled', 1);
$installer->setConfigData('persistent/options/lifetime', 31536000);
$installer->setConfigData('persistent/options/remember_enabled', 1);
$installer->setConfigData('persistent/options/remember_default', 1);
$installer->setConfigData('persistent/options/logout_clear', 1);
$installer->setConfigData('persistent/options/shopping_cart', 1);
$installer->setConfigData('persistent/options/customer', 1);
$installer->setConfigData('persistent/options/wishlist', 1);
$installer->setConfigData('persistent/options/recently_ordered', 1);

$installer->endSetup();
