<?php
/**
 * drop column created for shipping price
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Price
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$conn = $this->getConnection();

$installer->startSetup();

//  Drop and re-create foreign key to enable cascade actions
$conn->dropForeignKey($installer->getTable('tgc_price/adCode'),
        $installer->getFkName('tgc_price/adCode', 'customer_group_id', 'customer/customer_group', 'customer_group_id')
);

$conn->addForeignKey(
        $installer->getFkName('tgc_price/adCode', 'customer_group_id', 'customer/customer_group', 'customer_group_id'),
        $installer->getTable('tgc_price/adCode'),
        'customer_group_id',
        $installer->getTable('customer/customer_group'),
        'customer_group_id'
);

$installer->endSetup();
