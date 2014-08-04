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
 * Create table 'tgc_datamart/landing_media_code_alias'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('tgc_datamart/landing_media_code_alias'))
    ->addColumn('media_code_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned' => true,
        'nullable' => false,
        ), 'Media Code ID')
    ->addColumn('alias', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array('nullable' => false), 'Media Code Alias')
    ->addIndex(
        $installer->getIdxName('tgc_datamart/landing_media_code_alias', array('media_code_id')),
        array('media_code_id')
    )
    ->addIndex(
        $installer->getIdxName('tgc_datamart/landing_media_code_alias', array('alias')),
        array('alias')
    )
    ->setComment('Radio Landing Page Media Code Aliases');
$installer->getConnection()->createTable($table);

$installer->endSetup();
