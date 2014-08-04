<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$table = $installer->getTable('profs/professor');
$conn->addColumn($table, 'email', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'E-mail',
));
$conn->addColumn($table, 'facebook', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'Link to Facebook page',
));
$conn->addColumn($table, 'twitter', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'Link to Twitter',
));
$conn->addColumn($table, 'pinterest', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'Link to Pinterest',
));
$conn->addColumn($table, 'youtube', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'Link to YouTube channel',
));

$installer->endSetup();