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

$ukWebsite = $this->getUkWebsite();
$installer->setConfigData('general/country/allow', 'GB', 'websites', $ukWebsite->getId());
$installer->setConfigData('bazaarvoice/general/enable_bv', '0', 'websites', $ukWebsite->getId());

$auWebsite = $this->getAuWebsite();
$installer->setConfigData('bazaarvoice/general/enable_bv', '0', 'websites', $auWebsite->getId());

$installer->endSetup();
