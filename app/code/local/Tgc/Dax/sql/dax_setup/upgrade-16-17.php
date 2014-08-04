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

$attributesNeedRelaxedValidation = array(
    'price',
    'description',
    'short_description',
    'url_key',
);

foreach($attributesNeedRelaxedValidation as $attributeCode) {
    $attribute = $eavAttributeConfig->getAttribute(Mage_Catalog_Model_Product::ENTITY, $attributeCode);
    $attribute->setIsRequired(false);
    $attribute->save();
}

$installer->endSetup();