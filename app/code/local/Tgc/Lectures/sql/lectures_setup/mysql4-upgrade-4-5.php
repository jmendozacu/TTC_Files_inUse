<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();

$eavAttributeConfig = Mage::getModel('eav/config');
$attribute = $eavAttributeConfig->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'guidebook');
$attribute->setFrontendInput(Tgc_Lectures_Model_Observer::INPUT_TYPE_FILE_CUSTOM);
$attribute->save();

$installer->endSetup();
