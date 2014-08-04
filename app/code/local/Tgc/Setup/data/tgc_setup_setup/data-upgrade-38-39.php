<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$auWebsite = $installer->getAuWebsite()->getId();
$installer->setConfigData('design/theme/layout', 'au', 'websites', $auWebsite);

$ukWebsite = $installer->getUkWebsite()->getId();
$installer->setConfigData('design/theme/layout', 'uk', 'websites', $ukWebsite);

$installer->endSetup();
