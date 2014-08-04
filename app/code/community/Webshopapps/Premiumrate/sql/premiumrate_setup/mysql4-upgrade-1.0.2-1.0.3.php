<?php
/**
 * Shipping Table Rates
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Webshopapps
 * @package     Premiumrate
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;


$installer->startSetup();

/* Disable Virtual Products in Price Calculation */
$installer->setConfigData('carriers/premiumrate/include_virtual_price', 0);

$installer->endSetup();