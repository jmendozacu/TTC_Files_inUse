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

$conn->modifyColumn(
    $installer->getTable('tgc_datamart/emailLanding'),
    'sort_order',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'scale'     => '4',
        'precision' => '12',
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0.000',
        'comment'   => 'Sort Order',
    )
);

$installer->endSetup();
