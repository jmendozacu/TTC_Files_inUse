<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Dax_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$conn->addColumn($installer->getTable('customer/customer_group'), 'allow_coupons', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
    'length'    => null,
    'default'   => 1,
    'nullable'  => false,
    'comment'   => 'Allow Coupons',
));

$installer->endSetup();
