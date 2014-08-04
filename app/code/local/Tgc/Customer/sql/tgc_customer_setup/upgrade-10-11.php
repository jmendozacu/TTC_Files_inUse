<?php
/**
 * @category    TGC
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance
 * @author      Guidance Magento Team <magento@guidance.com>
 */

/* @var $installer Tgc_Customer_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();
$conn = $installer->getConnection();

$conn->modifyColumn(
    $installer->getTable('customer_entity'),
    'is_prospect',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
        'comment'  => 'Is Customer a Prospect?',
    )
);

$installer->updateAttribute(
    'customer',
    'is_prospect',
    array(
        'default_value' => '1',
    )
);

$installer->endSetup();
