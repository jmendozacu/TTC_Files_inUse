<?php
/**
 * Creates table with custom triggers
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Mview
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$db = $installer->getConnection();

$table = $db->newTable($installer->getTable('tgc_mview/custom_trigger'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Id')
    ->addColumn('subscriber_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'unsigned'  => true,
    ), 'Trigger Group')
    ->addColumn('event_name', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
        'nullable'  => false,
    ), 'Event Name')
    ->addColumn('trigger_body', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable'  => true,
    ), 'Trigger Body')
    ->addIndex($this->getIdxName('tgc_mview/custom_trigger', array('subscriber_id', 'event_name'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        array('subscriber_id', 'event_name'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->addForeignKey($this->getFkName('tgc_mview/custom_trigger', 'subscriber_id',
        'enterprise_mview/subscriber', 'subscriber_id'),
        'subscriber_id',
        $this->getTable('enterprise_mview/subscriber'),
        'subscriber_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Custom Trigger Table');

$db->createTable($table);

$installer->endSetup();
