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

$installer->setConfigData('rocketweb_podcast/settings/page_title', 'The Torch');

$installer->setConfigData('rocketweb_podcast/settings/subtitle', 'The Great Courses Podcast');

$installer->setConfigData('rocketweb_podcast/settings/explicit', 0);

$installer->setConfigData('rocketweb_podcast/settings/copyright', 2014);

$installer->setConfigData('rocketweb_podcast/settings/author_name', 'The Great Courses');

$installer->setConfigData('rocketweb_podcast/settings/author_email', 'itunes@teachco.com');

$installer->setConfigData('rocketweb_podcast/settings/category', 'Education');

$installer->setConfigData('rocketweb_podcast/settings/summary',
    'Hosted by our Chief Brand Officer Ed Leon, THE TORCH introduces you to the fascinating professors and experts who create the Great Courses. Each episode showcases several of our top professors in a fresh light, spotlights the great work they are doing, and gives you an opportunity to learn from their incredible insights.'
);

$installer->setConfigData('rocketweb_podcast/settings/page_description',
    'Hosted by our Chief Brand Officer Ed Leon, THE TORCH introduces you to the fascinating professors and experts who create the Great Courses. Each episode showcases several of our top professors in a fresh light, spotlights the great work they are doing, and gives you an opportunity to learn from their incredible insights.'
);

$installer->endSetup();