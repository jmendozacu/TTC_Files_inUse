<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
//Purpose is to delete all attributes with zero value.
$installer = $this;
$installer->startSetup();

$eavAttributeConfig = Mage::getModel('eav/config');
$attribute = $eavAttributeConfig->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'special_price');

$attributeId = $attribute->getId();
$table = $attribute->getBackend()->getTable();

if($table && $attributeId) {
    $where = array(
        'attribute_id = ?' => $attributeId,
        'value = ?'        => 0,
    );
    $conn->delete($table, $where);
}


$installer->endSetup();