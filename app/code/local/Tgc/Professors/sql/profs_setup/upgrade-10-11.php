<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$table = $installer->getTable('profs/professor');

$installer->getConnection()->addColumn($table, 'import_row_num', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'comment' => 'Import Row Number'
));

$installer->endSetup();
