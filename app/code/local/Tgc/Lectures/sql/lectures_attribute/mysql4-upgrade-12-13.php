<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();

$freeMarketingLectureAttributeCodes = array('marketing_free_lecture_from','marketing_free_lecture_to');
$eavAttributeConfig = Mage::getModel('eav/config');

foreach($freeMarketingLectureAttributeCodes as $attributeCode) {
    $attribute = $eavAttributeConfig->getAttribute(Mage_Catalog_Model_Product::ENTITY, $attributeCode);
    $attribute->setBackendModel('lectures/eav_entity_attribute_backend_freemarketinglecture');
    $attribute->save();
}

$installer->endSetup();
