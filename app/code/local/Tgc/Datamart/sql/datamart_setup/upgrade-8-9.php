<?php
/**
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Datamart_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$conn->addColumn(
    $installer->getTable('tgc_datamart/emailLanding_design'),
    'adcode',
    "INT(10) UNSIGNED DEFAULT NULL"
);

/*$conn->addIndex(
    $installer->getTable('tgc_datamart/emailLanding_design'),
    $installer->getIdxName('tgc_datamart/emailLanding_design', array('adcode')),
    array('adcode')
);

$conn->addForeignKey(
    $installer->getFkName('tgc_datamart/emailLanding_design', 'adcode', 'tgc_price/adCode', 'code'),
    $installer->getTable('tgc_datamart/emailLanding_design'), 'adcode',
    $installer->getTable('tgc_price/adCode'), 'code',
    Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
);
*/
$installer->endSetup();
