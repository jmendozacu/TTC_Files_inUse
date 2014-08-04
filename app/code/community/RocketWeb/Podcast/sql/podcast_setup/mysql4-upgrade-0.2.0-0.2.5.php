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
ALTER TABLE {$this->getTable('podcast/podcast')}
    ADD COLUMN explicit_content SMALLINT(6) unsigned default 0;
");

$installer->endSetup();