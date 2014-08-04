<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     SiteMap
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;

$installer->startSetup();

Mage::getModel('cms/page')
    ->setTitle('Site Map')
    ->setContent('&nbsp;')
    ->setIdentifier('site-map')
    ->setIsActive(true)
    ->setData(
        'layout_update_xml',
        '<reference name="content"><block type="tgc_sitemap/sitemap" name="tgc_sitemap" /></reference>'
    )
    ->setRootTemplate('one_column')
    ->setStores(array(0))
    ->setUnderVersionControl(false)
    ->save();

$installer->endSetup();
