<?php
/**
* @category    Podcast
* @package     Tgc
* @copyright   Copyright (c) 2014 Guidance
* @author      Chris Lohman <clohm@guidance.com>
*/
$installer = $this;

$installer->startSetup();

$installer->getConnection()->changeColumn(
    $installer->getTable('podcast/podcast'),
    'short_content',
    'short_content',
    'text NULL'
);

$installer->endSetup();
