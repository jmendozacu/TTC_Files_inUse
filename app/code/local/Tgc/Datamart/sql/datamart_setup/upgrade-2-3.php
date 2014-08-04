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
    DROP TABLE IF EXISTS `{$this->getTable('tgc_datamart/emailLanding_design')}`;
");

$table = $conn->newTable($installer->getTable('tgc_datamart/emailLanding_design'))
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
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
    ), 'Page Title')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Page Description')
    ->addColumn('keywords', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Page Keywords')
    ->addColumn('header_id', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
    ), 'Header Block ID')
    ->addColumn('footer_id', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
    ), 'Footer Block ID')
    ->addIndex(
        $installer->getIdxName(
            $installer->getTable('tgc_datamart/emailLanding_design'),
            array('category'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('category'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->addIndex($installer->getIdxName($installer->getTable('tgc_datamart/emailLanding_design'), array('category')),
        array('category'))
    ->setComment('DataMart Email Landing Page Design Table');

$conn->createTable($table);

$installer->endSetup();
