<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_DigitalLibrary_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$installer->run("
    DROP TABLE IF EXISTS `{$installer->getTable('tgc_dl/accessRights')}`;
");

$table = $conn->newTable($installer->getTable('tgc_dl/accessRights'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn(
        'course_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'unsigned' => true,
    ), 'Course ID')
    ->addColumn('format', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Media Format')
    ->addColumn('web_user_id', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
        'nullable' => false,
    ), 'Web User ID')
    ->addColumn('date_purchased', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
    ), 'Date Purchased')
    ->addIndex($installer->getIdxName('tgc_dl/accessRights', array('web_user_id')),
        array('web_user_id'))
    ->addIndex($installer->getIdxName('tgc_dl/accessRights', array('course_id')),
        array('course_id'))
    ->addIndex($installer->getIdxName('tgc_dl/accessRights', array('date_purchased')),
        array('date_purchased'))
    ->addForeignKey(
        $installer->getFkName(
            'tgc_dl/accessRights',
            'web_user_id',
            'customer/entity',
            'web_user_id'
        ),
        'web_user_id', $installer->getTable('customer/entity'), 'web_user_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName(
            'tgc_dl/accessRights',
            'course_id',
            'catalog/product',
            'entity_id'
        ),
        'course_id', $installer->getTable('catalog/product'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Digital Library Access Rights');

$conn->createTable($table);

$installer->endSetup();
