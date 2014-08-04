<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$professorTable = $conn->newTable($installer->getTable('profs/institution'))
    ->addColumn('institution_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned' => true, 'identity' => true, 'primary' => true, 'nullable' => false), 'Identity')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 128, array('nullable' => false), 'Name');

$conn->createTable($professorTable);

$installer->endSetup();