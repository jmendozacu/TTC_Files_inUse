<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     SiteMap
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;

$installer->startSetup();

$conn->update(
    $installer->getTable('cms/page'),
    array('identifier' => 'sitemap.xml'),
    $conn->quoteInto('identifier = ?', 'site-map')
);

$block = Mage::getModel('cms/block')->load('footer_bottom_area', 'identifier');

if ($block->getId()) {
    $block->setContent(
        str_ireplace(
            "{{store direct_url='site-map'}}",
            "{{store direct_url='sitemap.xml'}}",
            $block->getContent()
        )
    );
    $block->save();
}

$installer->endSetup();