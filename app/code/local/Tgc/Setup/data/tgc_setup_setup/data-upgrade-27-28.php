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

$auWebsite = $installer->getAuWebsite()->getId();

$installer->setConfigData('general/store_information/phone', '1800 461 951', 'websites', $auWebsite);

$installer->endSetup();