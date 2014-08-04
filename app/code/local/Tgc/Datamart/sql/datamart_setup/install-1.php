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
    DROP TABLE IF EXISTS `{$this->getTable('tgc_datamart/emailLanding')}`;
");

$table = $conn->newTable($installer->getTable('tgc_datamart/emailLanding'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn(
        'category',
        Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable' => false,
    ), 'Category')
    ->addColumn(
        'course_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'unsigned' => true,
    ), 'Course ID')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Sort Order')
    ->addColumn('markdown_flag', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Markdown Flag')
    ->addColumn('special_message', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Special Message')
    ->addColumn('date_expires', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
    ), 'Date Expires')
    ->addIndex(
        $installer->getIdxName(
            $installer->getTable('tgc_datamart/emailLanding'),
            array('category', 'course_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('category', 'course_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->addIndex($installer->getIdxName($installer->getTable('tgc_datamart/emailLanding'), array('sort_order')),
        array('sort_order'))
    ->addIndex($installer->getIdxName($installer->getTable('tgc_datamart/emailLanding'), array('date_expires')),
        array('date_expires'))
    ->setComment('DataMart Email Landing Page Table');

$conn->createTable($table);

$installer->endSetup();
