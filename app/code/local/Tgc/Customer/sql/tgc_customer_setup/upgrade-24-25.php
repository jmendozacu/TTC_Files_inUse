<?php
/**
 * User: mhidalgo
 * Date: 23/04/14
 * Time: 08:45
 */
/** @var $installer Mage_Customer_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->updateAttribute(
    "customer_address", 'telephone',  'is_required', false
);

$installer->endSetup();