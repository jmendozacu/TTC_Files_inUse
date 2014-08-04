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

$block = Mage::getModel('cms/block')->load('footer_bottom_area', 'identifier');

if ($block->getId()) {
    $block->setContent(
        str_ireplace(
            "/media/sitemaps/sitemap.xml",
            "{{store direct_url='site-map'}}",
            $block->getContent()
        )
    );
    $block->save();
}

$installer->endSetup();