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
 * Create table 'tgc_datamart/landing_banner'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('tgc_datamart/landing_banner'))
    ->addColumn('banner_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Banner ID')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Banner Title')
    ->addColumn('desktop_image', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Banner Image for Desktop')
    ->addColumn('mobile_image', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Banner Image for Mobile');
$installer->getConnection()->createTable($table);

/**
 * Create table 'tgc_datamart/landing_banner_adcode'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('tgc_datamart/landing_banner_adcode'))
    ->addColumn('banner_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Banner ID')
    ->addColumn('ad_code', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Ad Code')
    ->addIndex(
        $installer->getIdxName('tgc_datamart/landing_banner_adcode', array('ad_code')),
        array('ad_code')
    )
    ->addForeignKey(
        $installer->getFkName(
            'tgc_datamart/landing_banner_adcode',
            'banner_id',
            'tgc_datamart/landing_banner',
            'banner_id'
        ),
        'banner_id',
        $installer->getTable('tgc_datamart/landing_banner'),
        'banner_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('tgc_datamart/landing_banner_adcode', 'ad_code', 'tgc_price/adCode', 'code'),
        'ad_code',
        $installer->getTable('tgc_price/adCode'),
        'code',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Landing Page Banner To Ad Code Linkage Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();
