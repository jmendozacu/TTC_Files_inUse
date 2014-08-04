<?php
/**
 * User: mhidalgo
 * Date: 11/04/14
 * Time: 12:53
 */

$installer = $this;

$installer->startSetup();

$conn->addColumn($installer->getTable('podcast/podcast'), 'url_key', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 60,
    'default' => null,
    'comment' => 'Custom Url key',
    'after' => 'episode_duration',
));

$installer->endSetup();