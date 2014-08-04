<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$conn->addIndex(
    $installer->getTable('lectures/lectures'),
    $installer->getIdxName('lectures/lectures', array('lecture_number')),
    array('lecture_number')
);

$conn->addForeignKey(
    $installer->getFkName('lectures/lectures', 'product_id', 'catalog/product', 'entity_id'),
    $installer->getTable('lectures/lectures'), 'product_id',
    $installer->getTable('catalog/product'), 'entity_id'
);

$installer->endSetup();
