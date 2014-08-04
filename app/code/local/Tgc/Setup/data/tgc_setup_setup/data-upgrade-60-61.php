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

$installer->setConfigData('general/country/allow', 'AU', $installer->getAuWebsite());
$installer->setConfigData('general/country/allow', 'GB', $installer->getUkWebsite());

$installer->endSetup();
