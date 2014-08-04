<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Datamart_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$conn->addColumn(
    $installer->getTable('tgc_datamart/emailLanding'),
    'landing_page_type',
    "TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'"
);

$conn->addColumn(
    $installer->getTable('tgc_datamart/emailLanding_design'),
    'landing_page_type',
    "TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'"
);

$installer->endSetup();
