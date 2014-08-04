<?php
/**
 * User: mhidalgo
 * Date: 25/03/14
 * Time: 10:21
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE `{$installer->getTable('tgc_zmag/zmag')}` ADD `customer_type` int(11) NULL;
");

$installer->endSetup();