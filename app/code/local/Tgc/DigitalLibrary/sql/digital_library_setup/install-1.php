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

$conn->addIndex(
    $installer->getTable('customer_entity'),
    $installer->getIdxName(
        $installer->getTable('customer_entity'),
        array('web_user_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
    ),
    array('web_user_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

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
    ->setComment('Digital Library Access Rights');

$conn->createTable($table);

$installer->run("
    DROP TABLE IF EXISTS `{$installer->getTable('tgc_dl/crossPlatformResume')}`;
");

$table = $conn->newTable($installer->getTable('tgc_dl/crossPlatformResume'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn(
        'lecture_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'unsigned' => true,
    ), 'Lecture ID')
    ->addColumn('web_user_id', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
        'nullable' => false,
    ), 'Web User ID')
    ->addColumn('progress', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Progress')
    ->addColumn('download_date', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
    ), 'Download Date')
    ->addColumn('stream_date', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
    ), 'Stream Date')
    ->addIndex($installer->getIdxName('tgc_dl/crossPlatformResume', array('lecture_id')),
        array('lecture_id'))
    ->addIndex($installer->getIdxName('tgc_dl/crossPlatformResume', array('web_user_id')),
        array('web_user_id'))
    ->addForeignKey(
        'FK_DIGITAL_LIBRARY_CPR_LECTURE_ID_LECTURES',
        'lecture_id', $installer->getTable('lectures/lectures'), 'id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        'FK_DIGITAL_LIBRARY_CPR_WEB_USER_ID_CUSTOMER_ENTITY',
        'web_user_id', $installer->getTable('customer/entity'), 'web_user_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Digital Library Cross Platform Resume');

$conn->createTable($table);

$installer->endSetup();
