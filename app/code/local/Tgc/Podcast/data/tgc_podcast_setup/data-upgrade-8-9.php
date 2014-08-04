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

// Remove Mana SEO url redirects from erroneously created Podcast CMS page
$installer->getConnection()->delete('m_seo_url', 'description LIKE "%podcast%"');

$installer->endSetup();