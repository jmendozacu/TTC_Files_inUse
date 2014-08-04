<?php
/**
* @category    Podcast
* @package     Tgc
* @copyright   Copyright (c) 2014 Guidance
* @author      Chris Lohman <clohm@guidance.com>
*/
$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('tgc_podcast/podcast_product')};
CREATE TABLE {$this->getTable('tgc_podcast/podcast_product')} (
                  `id` int(250) unsigned NOT NULL auto_increment,
                  `podcast_id` mediumint(9) default NULL,
                  `product_id` mediumint(9) default NULL,
                  PRIMARY KEY  (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");

$installer->endSetup();
