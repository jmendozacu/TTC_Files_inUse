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

$conn->addColumn($table, 'url_key', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => 100,
    'comment' => 'URL key'
));
$conn->addIndex($table, $installer->getIdxName($table, array('url_key')), array('url_key'), $conn::INDEX_TYPE_UNIQUE);

$installer->endSetup();
