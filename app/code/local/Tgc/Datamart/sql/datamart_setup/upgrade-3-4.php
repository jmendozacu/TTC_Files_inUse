<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Datamart_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;

$installer->startSetup();

$installer->run("
    DROP TABLE IF EXISTS `{$this->getTable('tgc_datamart/customerUpsell')}`;
");

$table = $conn->newTable($installer->getTable('tgc_datamart/customerUpsell'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn(
        'segment_group',
        Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable' => false,
    ), 'Segment Group')
    ->addColumn('course_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Course ID')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Sort Order')
    ->addIndex(
        $installer->getIdxName(
            $installer->getTable('tgc_datamart/customerUpsell'),
            array('segment_group', 'course_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('segment_group', 'course_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->addIndex($installer->getIdxName($installer->getTable('tgc_datamart/customerUpsell'), array('sort_order')),
        array('sort_order'))
    ->setComment('DataMart Customer Upsell Table');

$conn->createTable($table);

$installer->run("
    DROP TABLE IF EXISTS `{$this->getTable('tgc_datamart/anonymousUpsell')}`;
");

$table = $conn->newTable($installer->getTable('tgc_datamart/anonymousUpsell'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn('subject_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Subject ID')
    ->addColumn('course_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Course ID')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Sort Order')
    ->addIndex(
    $installer->getIdxName(
        $installer->getTable('tgc_datamart/anonymousUpsell'),
        array('subject_id', 'course_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('subject_id', 'course_id'),
    array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
)
    ->addIndex($installer->getIdxName($installer->getTable('tgc_datamart/anonymousUpsell'), array('sort_order')),
    array('sort_order'))
    ->setComment('DataMart Anonymous Upsell Table');

$conn->createTable($table);

$installer->endSetup();
