<?php
/**
 * Websites setup
 * 
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
$installer = $this;
 
$installer->startSetup();

// ------------------------------------------------------
// --- US website ---------------------------------------
// ------------------------------------------------------

$usWebsite = Mage::getModel('core/website')
    ->load(1)
    ->setName('US')
    ->setSortOrder(10)
    ->save();

$usStoreGroup = Mage::getModel('core/store_group')
    ->load(1)
    ->setWebsiteId($usWebsite->getId())
    ->setName('US Store')
    ->save();

$usEnglishStore = Mage::getModel('core/store')
    ->load(1)
    ->setWesiteId($usWebsite->getId())
    ->setGroupId($usStoreGroup->getId())
    ->setName('English')
    ->setSortOrder(0)
    ->setIsActive(true)
    ->save(); 

// ------------------------------------------------------
// --- UK website ---------------------------------------
// ------------------------------------------------------

$ukWebsite = Mage::getModel('core/website')
    ->setName('UK')
    ->setCode($installer::UK_WEBSITE_CODE)
    ->setSortOrder(20)
    ->save();

$ukStoreGroup = Mage::getModel('core/store_group')
    ->setWebsiteId($ukWebsite->getId())
    ->setName('UK Store')
    ->save();

Mage::getModel('core/store')
    ->setWesiteId($ukWebsite->getId())
    ->setGroupId($ukStoreGroup->getId())
    ->setName('English')
    ->setCode($installer::UK_EN_STORE_CODE)
    ->setSortOrder(0)
    ->setIsActive(true)
    ->save();


// ------------------------------------------------------
// --- Australian website -------------------------------
// ------------------------------------------------------

$auWebsite = Mage::getModel('core/website')
    ->setName('Australia')
    ->setCode($installer::AU_WEBSITE_CODE)
    ->setSortOrder(30)
    ->save();

$auStoreGroup = Mage::getModel('core/store_group')
    ->setWebsiteId($auWebsite->getId())
    ->setName('Australian Store')
    ->save();

Mage::getModel('core/store')
    ->setWesiteId($auWebsite->getId())
    ->setGroupId($auStoreGroup->getId())
    ->setName('English')
    ->setCode($installer::AU_EN_STORE_CODE)
    ->setSortOrder(0)
    ->setIsActive(true)
    ->save();

$installer->endSetup();
