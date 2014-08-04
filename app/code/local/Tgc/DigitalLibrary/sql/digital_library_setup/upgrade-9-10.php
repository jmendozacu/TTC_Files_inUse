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
$conn = $installer->getConnection();

$installer->run("
    DROP TABLE IF EXISTS `{$installer->getTable('tgc_dl/akamaiContent')}`;
");

$table = $conn->newTable($installer->getTable('tgc_dl/akamaiContent'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity ID')
    ->addColumn('course_id', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
    ), 'Course ID')
    ->addColumn('guidebook_file_name', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Guidebook File Name')
    ->addColumn('guidebook_url_prefix', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Guidebook URL Prefix')
    ->addColumn('transcript_file_name', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Transcript File Name')
    ->addColumn('transcript_url_prefix', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Transcript URL Prefix')
    ->addIndex(
        $installer->getIdxName(
            $installer->getTable('tgc_dl/akamaiContent'),
            array('course_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('course_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->addForeignKey(
        $installer->getFkName(
            'tgc_dl/akamaiContent',
            'course_id',
            'catalog/product',
            'sku'
        ),
        'course_id', $installer->getTable('catalog/product'), 'sku',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Digital Library Akamai Content');

$conn->createTable($table);

$installer->endSetup();
