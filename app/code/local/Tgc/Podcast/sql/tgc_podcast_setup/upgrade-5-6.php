<?php
/**
* @category    Podcast
* @package     Tgc
* @copyright   Copyright (c) 2014 Guidance
* @author      Chris Lohman <clohm@guidance.com>
*/
$installer = $this;

$installer->startSetup();

$conn->addColumn($installer->getTable('podcast/podcast'), 'episode_image', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'default'   => null,
    'comment'   => 'episode image',
    'after'     => 'meta_keywords',
));

$installer->endSetup();
