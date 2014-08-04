<?php
/**
 * Root categories for stores
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$defaultCategory = $installer->getCategory('Default Category');

$ukStore = Mage::getModel('core/store')
    ->load(Tgc_Setup_Model_Resource_Setup::UK_EN_STORE_CODE);
$ukGroup = $ukStore->getGroup();
$ukGroup->setRootCategoryId($defaultCategory->getId())
    ->save();

$auStore = Mage::getModel('core/store')
    ->load(Tgc_Setup_Model_Resource_Setup::AU_EN_STORE_CODE);
$auGroup = $auStore->getGroup();
$auGroup->setRootCategoryId($defaultCategory->getId())
    ->save();

$installer->endSetup();