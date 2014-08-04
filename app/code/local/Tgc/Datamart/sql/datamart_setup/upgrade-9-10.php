<?php
/**
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Datamart_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;

$installer->startSetup();

$installer->run("
    DROP TABLE IF EXISTS `{$this->getTable('tgc_datamart/adcode')}`;
");

$table = $conn->newTable($installer->getTable('tgc_datamart/adcode'))
    ->addColumn('adcode_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'identity'  => true,
    'unsigned'  => true,
    'nullable'  => false,
    'primary'   => true,
), 'Adcode ID')
    ->addColumn('email_landing_id',Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'unsigned' => true,
        'default'  => '0',
), 'Email Landing Page Design')
    ->addColumn('code', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'unsigned' => true,
), 'Code')
    ->addColumn('mobile_banner_image', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
), 'Mobile Banner Image')
    ->addColumn('desktop_banner_image', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
), 'Desktop Banner Image')
    ->addIndex($installer->getIdxName('tgc_datamart/adcode', array('code')),
    array('code'))
    ->addIndex($installer->getIdxName('tgc_datamart/adcode', array('email_landing_id')),
    array('email_landing_id'))
    ->addForeignKey(
    $installer->getFkName('tgc_datamart/adcode', 'email_landing_id', 'tgc_datamart/emailLanding_design', 'entity_id'),
    'email_landing_id', $installer->getTable('tgc_datamart/emailLanding_design'), 'entity_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('tgc_datamart/adcode', 'code', 'tgc_price/adCode', 'code'),
        'code', $installer->getTable('tgc_price/adCode'), 'code',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Adcode Table');

$conn->createTable($table);

$installer->endSetup();
