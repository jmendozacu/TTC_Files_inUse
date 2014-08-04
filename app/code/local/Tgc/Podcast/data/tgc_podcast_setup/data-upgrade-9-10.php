<?php
/**
* @author      Guidance Magento Team <magento@guidance.com>
* @category    Guidance
* @package     Podcast
* @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
*/
/* @var $installer Mage_Core_Model_Resource_Setup */


$installer = $this;

$installer->startSetup();

// Change path from "podcast" to "podcasts"
$installer->setConfigData('rocketweb_podcast/settings/route', 'podcasts');

$installer->endSetup();