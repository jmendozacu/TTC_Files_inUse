<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
$installer = $this;
$installer->startSetup();

$entity = Mage_Catalog_Model_Product::ENTITY;
$productEntityTypeId = Mage::getModel('catalog/product')->getResource()->getTypeId();
$option = array(
    'attribute_id' => $installer->getAttributeId($productEntityTypeId, 'media_format')
);
$option['value']['option6'][0] = 'Transcript Book';
$option['value']['option7'][0] = 'Digital Transcript';

$installer->addAttributeOption($option);
$installer->endSetup();
