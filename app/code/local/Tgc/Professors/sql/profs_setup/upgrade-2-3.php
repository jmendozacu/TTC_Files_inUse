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

$conn->addColumn($installer->getTable('profs/professor'), 'institution_id', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'unsigned'  => true,
    'comment'   => 'Institution ID'
));
$conn->addForeignKey(
    $installer->getFkName('profs/professor', 'institution_id', 'profs/institution', 'institution_id'),
    $installer->getTable('profs/professor'), 'institution_id',
    $installer->getTable('profs/institution'), 'institution_id'
);

$installer->endSetup();