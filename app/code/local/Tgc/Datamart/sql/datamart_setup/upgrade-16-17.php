<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Datamart_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * Create table 'tgc_datamart/landing_media_code'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('tgc_datamart/landing_media_code'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Entity ID')
    ->addColumn('media_code', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(), 'Media Code')
    ->addColumn('ad_code', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Ad Code')
    ->addIndex(
        $installer->getIdxName('tgc_datamart/landing_media_code', array('media_code')),
        array('media_code')
    )
    ->addForeignKey(
        $installer->getFkName('tgc_datamart/landing_media_code', 'ad_code', 'tgc_price/adCode', 'code'),
        'ad_code',
        $installer->getTable('tgc_price/adCode'),
        'code',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Media Codes for Radio Landing Pages');
$installer->getConnection()->createTable($table);

$installer->endSetup();
