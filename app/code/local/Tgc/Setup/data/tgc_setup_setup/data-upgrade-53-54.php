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

$installer->setConfigData('catalog/seo/product_url_suffix', '')
    ->setConfigData('catalog/seo/category_url_suffix', '')
    ->setConfigData('catalog/seo/category_canonical_tag', 1)
    ->setConfigData('catalog/seo/product_canonical_tag', 1);

$installer->endSetup();