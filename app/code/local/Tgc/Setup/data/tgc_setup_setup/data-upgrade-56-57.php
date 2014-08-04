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
$installer->setConfigData('design/theme/layout', '', 'websites', $auWebsite, 1);

$ukWebsite = $installer->getUkWebsite()->getId();
$installer->setConfigData('design/theme/layout', '', 'websites', $ukWebsite, 1);

$installer->setConfigData('tgc_seewhy/js/customer_code', 'AB45452213');
$installer->setConfigData('tgc_seewhy/js/customer_code', 'AD03063645', 'websites', $auWebsite);
$installer->setConfigData('tgc_seewhy/js/customer_code', 'AC68731557', 'websites', $ukWebsite);

$installer->endSetup();
