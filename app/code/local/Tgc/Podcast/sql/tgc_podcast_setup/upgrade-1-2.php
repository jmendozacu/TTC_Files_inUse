<?php
/**
* @category    Podcast
* @package     Tgc
* @copyright   Copyright (c) 2014 Guidance
* @author      Chris Lohman <clohm@guidance.com>
*/
$installer = $this;

$installer->startSetup();

// Remove 2 CMS pages for Podcast that were used for frontend development

$page = Mage::getModel('cms/page')->load('podcast', 'identifier');
$page->delete();

$page = Mage::getModel('cms/page')->load('podcast/episode', 'identifier');
$page->delete();

$installer->endSetup();
