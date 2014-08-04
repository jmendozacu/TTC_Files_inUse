<?php
/**
 * Theme Setup
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->setConfigData('spp/setting/productname', 0)
    ->setConfigData('spp/setting/description', 0)
    ->setConfigData('spp/setting/shortdescription', 0);

$installer->endSetup();