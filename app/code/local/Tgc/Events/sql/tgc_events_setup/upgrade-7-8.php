<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;

$installer->startSetup();

$installer->getConnection()->changeColumn(
    $installer->getTable('tgc_events/locations'),
    'location',
    'location',
    'varchar(255) NOT NULL'
);

$installer->getConnection()->changeColumn(
    $installer->getTable('tgc_events/locations'),
    'location_code',
    'location_code',
    'varchar(255) NOT NULL'
);

$installer->getConnection()->changeColumn(
    $installer->getTable('tgc_events/locations'),
    'location_image',
    'location_image',
    'varchar(255) NULL'
);
$installer->endSetup();
