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
DROP TABLE IF EXISTS {$this->getTable('podcast/store')};
CREATE TABLE {$this->getTable('podcast/store')} (
`podcast_id` smallint(6) unsigned,
`store_id` smallint(6) unsigned
) ENGINE = InnoDB DEFAULT CHARSET = utf8;
");

$installer->endSetup();
