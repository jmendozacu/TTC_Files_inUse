<?php
/**
 * Solr search
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Solr
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Solr_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$installer->setConfigData('catalog/search/solr_server_suggestion_enabled', 1);
$installer->setConfigData('catalog/search/solr_server_suggestion_count', 3);

$installer->endSetup();
