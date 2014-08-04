<?php
/**
 * Adds TaxableAmount column.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$installer->getConnection()->modifyColumn($installer->getTable('tax/sales_order_tax_item'), 'taxable_amount', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'scale'     => 4,
    'precision' => 12,
    'nullable'  => true,
    'comment'   => 'Item Taxable Amount'
));

$installer->endSetup();