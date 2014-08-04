<?php
/**
 * Tgc_ManaPro
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     ManaPro
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();

$installer->setConfigData('mana/js/jquery', 'unload');
$installer->setConfigData('mana_filters/display/price', 'css_checkboxes');
$installer->setConfigData('mana_filters/advanced/clear', 1);

$installer->endSetup();
