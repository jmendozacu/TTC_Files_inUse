<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */

$installer = $this;
$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('podcast/podcast')};
CREATE TABLE {$this->getTable('podcast/podcast')} (
  `podcast_id` int(11) unsigned NOT NULL auto_increment,
  `title` VARCHAR(255) NOT NULL,
  `status` SMALLINT(6) unsigned default 0,
  `short_content` VARCHAR(250) default NULL,
  `long_content` TEXT,
  `meta_keywords` VARCHAR(255) default NULL,
  `file_name` VARCHAR(255) default NULL,
  `author_name` VARCHAR(255) default NULL,
  `author_email` VARCHAR(255) default NULL,
  `created_time` TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
  `updated_time` TIMESTAMP default '0000-00-00 00:00:00',
  PRIMARY KEY (`podcast_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();