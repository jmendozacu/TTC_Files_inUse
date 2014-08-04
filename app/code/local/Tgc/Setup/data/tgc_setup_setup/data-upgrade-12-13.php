<?php
/**
 * Theme Setup
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$usWebsite = $installer->getUsWebsite()->getId();
$ukWebsite = $installer->getUkWebsite()->getId();
$auWebsite = $installer->getAuWebsite()->getId();

$sites = array(
    $usWebsite => 'us',
    $ukWebsite => 'uk',
    $auWebsite => 'au',
);

foreach ($sites as $id => $theme) {
    $installer->setConfigData('design/theme/template', $theme, 'websites', $id);
    $installer->setConfigData('design/theme/locale', $theme, 'websites', $id);
}

$installer->endSetup();
