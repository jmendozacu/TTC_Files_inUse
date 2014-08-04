<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;

$installer->startSetup();

$conn->addColumn($installer->getTable('events'), 'event_short_description', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'default'   => null,
    'comment'   => 'event short description',
    'after'     => 'event_type',
));

$installer->endSetup();
