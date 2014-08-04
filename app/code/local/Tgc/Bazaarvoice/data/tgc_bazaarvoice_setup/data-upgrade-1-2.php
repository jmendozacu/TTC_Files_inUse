<?php
/**
 * Bazaarvoice
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Bazaarvoice
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Bazaarvoice_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

//general
$installer->setConfigData('bazaarvoice/general/enable_bv', '0');
$installer->setConfigData('bazaarvoice/general/environment', 'staging');
$installer->setConfigData('bazaarvoice/general/client_name', '');
$installer->setConfigData('bazaarvoice/general/ftp_password', '');
$installer->setConfigData('bazaarvoice/general/deployment_zone', '');
$installer->setConfigData('bazaarvoice/general/locale', 'en_US');
$installer->setConfigData('bazaarvoice/general/enable_cloud_seo', '1');
$installer->setConfigData('bazaarvoice/general/cloud_seo_key', '');
$installer->setConfigData('bazaarvoice/general/display_code', '');
$installer->setConfigData('bazaarvoice/general/enable_roibeacon', '1');

//ratings and reviews
$installer->setConfigData('bazaarvoice/rr/enable_rr', '1');
$installer->setConfigData('bazaarvoice/rr/do_show_content_js', '');
$installer->setConfigData('bazaarvoice/rr/enable_inline_ratings', '1');

//q and a
$installer->setConfigData('bazaarvoice/qa/enable_qa', '1');
$installer->setConfigData('bazaarvoice/qa/do_show_content_js', '');

//feeds
$installer->setConfigData('bazaarvoice/feeds/enable_product_feed', '1');
$installer->setConfigData('bazaarvoice/feeds/enable_purchase_feed', '1');
$installer->setConfigData('bazaarvoice/feeds/triggering_event', 'shipping');
$installer->setConfigData('bazaarvoice/feeds/admin_email', '');
$installer->setConfigData('bazaarvoice/feeds/generation_scope', 'website');

//determine environment
$baseUrl = $_SERVER['SERVER_NAME'];
$isDev = false;
switch($baseUrl) {
    case 'gc.dev.guidance.com':
    case 'us.gc.dev.guidance.com':
    case 'uk.gc.dev.guidance.com':
    case 'aus.gc.dev.guidance.com':
        $isDev = true;
        break;
}

if ($isDev) {
    $installer->setConfigData('bazaarvoice/general/enable_bv', '1');
}

$installer->endSetup();
