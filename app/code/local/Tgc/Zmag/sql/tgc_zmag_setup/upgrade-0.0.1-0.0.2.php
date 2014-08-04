<?php
/**
 * User: mhidalgo
 * Date: 05/03/14
 * Time: 10:12
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();
$websites = Mage::getModel('core/website')->getCollection();

$urlRewrite = "digitalcatalog";

$urlOrigin = 'zmag/dcatalog';

foreach ($websites as $website) {

    $websiteId = $website->getId();
    $store = $website->getDefaultStore();

    $redirect = Mage::getModel('enterprise_urlrewrite/redirect');
    $redirect->setStoreId($store->getId())
        ->setOptions('RP')
        ->setIdPath($urlRewrite . "_" . $websiteId)
        ->setTargetPath($urlOrigin)
        ->setIdentifier($urlRewrite)
        ->save();

    $rewrite = Mage::getModel('enterprise_urlrewrite/url_rewrite');
    $rewrite->setIsSystem(0)
        ->setStoreId($store->getId())
        //->setOptions('RP')
        ->setIdPath($urlRewrite . "_" . $websiteId)
        ->setTargetPath($urlOrigin)
        ->setRequestPath($urlRewrite)
        ->setIdentifier($urlRewrite)
        ->setValueId($redirect->getId())
        ->setEntityType(1)
        ->save();
}

$installer->endSetup();