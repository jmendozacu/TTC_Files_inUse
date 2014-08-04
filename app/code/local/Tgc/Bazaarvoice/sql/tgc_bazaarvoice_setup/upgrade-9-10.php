<?php
/**
 * Bazaarvoice
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Bazaarvoice
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Bazaarvoice_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$entity = Mage_Catalog_Model_Product::ENTITY;
$ratingAttribute = Tgc_Bazaarvoice_Model_Convert_Adapter_Review::RATING_ATTRIBUTE;

$attributeId = $installer->getAttributeId($entity, $ratingAttribute);

$db = $installer->getConnection();

$db->beginTransaction();
try {
    $select = $db->select()->from($installer->getTable('eav/attribute_option_value'), 'option_id')
        ->where($db->quoteIdentifier('value') . ' IN (?)', array('2-stars-and-up', '1-stars-and-up'));
    $stmt = $db->query($select);
    while ($row = $stmt->fetch()) {
        $db->delete(
            $installer->getTable('eav/attribute_option'),
            $db->quoteInto('option_id = ?', $row['option_id']). ' AND '.
            $db->quoteInto('attribute_id = ?', $attributeId)
        );
    }
    $db->commit();
} catch (Exception $e) {
    $db->rollBack();
    throw $e;
}

$installer->endSetup();
