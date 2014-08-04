<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;

$installer->startSetup();

$conn->addColumn($installer->getTable('events_locations'), 'is_active', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'default'   => 0,
    'comment'   => 'status column',
    'after'     => 'sort_order',
));

$conn->addColumn($installer->getTable('events_types'), 'is_active', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'default'   => 0,
    'comment'   => 'status column',
    'after'     => 'sort_order',
));

$installer->run("update {$installer->getTable('events_locations')}
    set `is_active` = 1
");

$installer->run("update {$installer->getTable('events_types')}
    set `is_active` = 1
");

$installer->endSetup();
