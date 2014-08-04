<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$table = $installer->getTable('profs/professor');
$conn->addColumn($table, 'testimonial', array(
    'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'Testimonial',
));

$installer->endSetup();