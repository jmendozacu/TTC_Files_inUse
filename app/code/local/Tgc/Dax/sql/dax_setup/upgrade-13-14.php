<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Dax_Model_Resource_Catalog_Setup */

$installer = $this;
$installer->startSetup();

$eavAttributeConfig = Mage::getModel('eav/config');
$attribute = $eavAttributeConfig->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'meta_description');
$attribute->setBackendType('text');
$attribute->save();

$installer->endSetup();