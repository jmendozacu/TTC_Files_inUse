<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Datamart_Model_Resource_Setup */
$installer = $this;

$installer->getConnection()->addColumn(
    $installer->getTable('tgc_datamart/landing_banner'),
    'set_sku',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'   => 255,
        'nullable' => true,
        'default'  => null,
        'comment'  => 'Set Product SKU'
    )
);

$installer->getConnection()->addColumn(
    $installer->getTable('tgc_datamart/landing_banner'),
    'set_text',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'   => '64k',
        'nullable' => true,
        'default'  => null,
        'comment'  => 'Set Product Text'
    )
);

$installer->getConnection()->dropColumn($installer->getTable('tgc_datamart/emailLanding_design'), 'set_sku');
$installer->getConnection()->dropColumn($installer->getTable('tgc_datamart/emailLanding_design'), 'set_text');
