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

$page = Mage::getModel('cms/page')
            ->load('natgeo', 'identifier');

$websites = array(
    $installer->getUsWebsite(),
    $installer->getUkWebsite(),
    $installer->getAuWebsite(),
);

foreach ($websites as $website) {
    Mage::getModel('adcoderouter/redirects')->setData(array(
        'search_expression' => 'tgc/NatGeo/Natgeo.aspx',
        'dax_key' => 0,
        'start_date' => '2014-01-01',
        'end_date' => '2030-04-07',
        'ad_code' => 96828,
        'cms_page_id' => $page->getId(),
        'ad_code_from_param' => 'ai',
        'store_id' => $website->getDefaultStore()->getId(),
    ))->save();
    Mage::getModel('adcoderouter/redirects')->setData(array(
        'search_expression' => 'natgeo',
        'dax_key' => 0,
        'start_date' => '2014-01-01',
        'end_date' => '2030-04-07',
        'ad_code' => 96828,
        'cms_page_id' => $page->getId(),
        'ad_code_from_param' => 'ai',
        'store_id' => $website->getDefaultStore()->getId(),
    ))->save();
}

$installer->endSetup();