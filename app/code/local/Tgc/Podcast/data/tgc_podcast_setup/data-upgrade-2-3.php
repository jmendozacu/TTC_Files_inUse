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

// Change default layout from "2 column" to "1 column"
$installer->setConfigData('rocketweb_podcast/settings/layout', 'page/1column.phtml');

$installer->endSetup();