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

$installer->setConfigData('mana_filters/positioning/categories', 'hide');

$installer->endSetup();
