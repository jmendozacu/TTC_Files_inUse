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

$db->beginTransaction();
try {
    $db->delete($installer->getTable('mana_filters/filter2'), $db->quoteInto('code = ?', 'new_release'));
    $db->delete($installer->getTable('mana_filters/filter2_store'), $db->quoteInto('name = ?', 'New Release'));
    $db->commit();
} catch (Exception $e) {
    $db->rollBack();
    throw $e;
}

$installer->endSetup();