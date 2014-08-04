<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_NewRelease
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
$db = $installer->getConnection();

$entity = Mage_Catalog_Model_Product::ENTITY;

$db->beginTransaction();
try {

    $db->delete(
        $installer->getTable('eav/attribute'), $db->quoteInto('attribute_code = ?', 'new_release')
    );

    $installer->updateAttribute($entity, 'news_to_date', 'is_searchable', 1);
    $installer->updateAttribute($entity, 'news_to_date', 'used_for_sort_by', 1);
    $db->commit();
} catch (Exception $e) {
    $db->rollBack();
    throw $e;
}

$installer->endSetup();