<?php
/**
 * Promo
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     CurrencySetup
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
$installer = $this;
$installer->startSetup();

$configModel = Mage::getModel('core/config');
$configModel->saveConfig('currency/options/allow', 'AUD,GBP,USD', 'default', 0);
$configModel->saveConfig('currency/options/customsymbol', serialize(array('AUD' => '$')), 'default', 0);

$installer->endSetup();