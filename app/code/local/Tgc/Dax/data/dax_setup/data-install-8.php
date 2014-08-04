<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */


$installer = $this;

$installer->startSetup();

$installer->setConfigData('cataloginventory/item_options/manage_stock', 0);

$installer->endSetup();