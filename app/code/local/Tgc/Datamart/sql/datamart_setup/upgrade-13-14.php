<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Datamart_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->getConnection()->modifyColumn(
    $installer->getTable('tgc_datamart/emailLanding_design'),
    'header_id',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 255,
        'nullable'  => true,
        'default'   => null,
        'comment'   => 'Header Block ID'
    )
);

$installer->getConnection()->modifyColumn(
    $installer->getTable('tgc_datamart/emailLanding_design'),
    'footer_id',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 255,
        'nullable'  => true,
        'default'   => null,
        'comment'   => 'Footer Block ID'
    )
);

$installer->endSetup();
