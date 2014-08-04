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
    DROP COLUMN explicit_content;
");

$installer->endSetup();