<?php
/**
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    The Great Courses
 * @package     Tgc_Customer
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();

//this adds a flag that is used to identify transcript products.
foreach (array('quote_item','order_item') as $entityType) {
    $installer->addAttribute($entityType, 'is_transcript_product', array('type' => Varien_Db_Ddl_Table::TYPE_INTEGER,'default' => 0));
    $installer->addAttribute($entityType, 'transcript_parent_item_id', array('type' => Varien_Db_Ddl_Table::TYPE_INTEGER));
    $installer->addAttribute($entityType, 'transcript_type', array('type' => Varien_Db_Ddl_Table::TYPE_VARCHAR));
}

$installer->endSetup();