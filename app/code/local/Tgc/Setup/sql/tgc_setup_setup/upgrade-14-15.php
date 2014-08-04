<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$conn->addColumn($installer->getTable('sales/order_payment'), 'paymentech_profile_id', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'  => 16,
    'comment' => 'Card profile ID from Chase Paymentech',
));

$installer->endSetup();
