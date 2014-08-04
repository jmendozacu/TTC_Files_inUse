<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

/** @var $installer Tgc_Datamart_Model_Resource_Setup */
$installer = $this;

$installer->getConnection()->addColumn(
    $installer->getTable('sales/quote_item'),
    'from_buffet_landing',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'unsigned' => true,
        'nullable' => false,
        'default'  => 0,
        'comment'  => 'Item added from Buffet Landing Page flag'
    )
);
