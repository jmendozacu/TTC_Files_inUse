<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;

$installer->startSetup();

$conn->addColumn($installer->getTable('events'), 'event_type', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'default'   => null,
    'comment'   => 'event type',
    'after'     => 'event_venu',
));

$conn->addColumn($installer->getTable('events'), 'event_date_location_description', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'default'   => null,
    'comment'   => 'event date location description',
    'after'     => 'event_type',
));

$conn->addColumn($installer->getTable('events'), 'global_featured_event', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'default'   => null,
    'comment'   => 'global featured event',
    'after'     => 'event_date_location_description',
));

$conn->addColumn($installer->getTable('events'), 'location_featured_event', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'default'   => null,
    'comment'   => 'location featured event specific to the location this event is assigned to.',
    'after'     => 'global_featured_event',
));

$installer->endSetup();
