<?php
/**
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    The Great Courses
 * @package     Adcoderouter
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('adcoderouter/redirects');

$conn->dropIndex(
    $tableName,
    $installer->getIdxName(
        'adcoderouter/redirects',
        array('search_expression','ad_code','store_id','start_date','end_date'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    )
);

$conn->addIndex(
    $tableName,
    $installer->getIdxName(
        'adcoderouter/redirects',
        array('search_expression','store_id','start_date','end_date'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('search_expression','store_id','start_date','end_date'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$installer->endSetup();