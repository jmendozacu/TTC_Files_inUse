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
    $installer->getTable('tgc_events/types'),
    'type_icon',
    'type_icon',
    'varchar(100) NOT NULL DEFAULT ""'
);
$installer->endSetup();
