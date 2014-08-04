<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Cms_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$page = Mage::getModel('cms/page')->load('home-logedin', 'identifier');
$page->delete();

$page = Mage::getModel('cms/page')->load('home', 'identifier');
$page->setRootTemplate('homepage_two_columns_right');
$page->save();

$installer->endSetup();
