<?php
/**
 * @category    Podcast
 * @package     Tgc
 * @copyright   Copyright (c) 2014 Guidance
 * @author      Chris Lohman <clohm@guidance.com>
 */
$installer = $this;

$installer->startSetup();

$conn->addColumn($installer->getTable('podcast/podcast'), 'episode_duration', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'default' => null,
    'comment' => 'episode duration',
    'after' => 'episode_number',
));

$installer->endSetup();
