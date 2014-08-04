<?php
/**
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Setup
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$ukWebsite = $installer->getUkWebsite()->getId();
$auWebsite = $installer->getAuWebsite()->getId();

$installer->setConfigData('general/country/default', 'GB', 'websites', $ukWebsite);
$installer->setConfigData('general/country/default', 'AU', 'websites', $auWebsite);

$installer->endSetup();