<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Cms_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->addColumn(
    $installer->getTable('tgc_cms/heroCarousel'),
    'user_type',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'length'    => null,
        'default'   => 2,
        'nullable'  => false,
        'comment'   => 'User Type',
    )
);

$installer->endSetup();
