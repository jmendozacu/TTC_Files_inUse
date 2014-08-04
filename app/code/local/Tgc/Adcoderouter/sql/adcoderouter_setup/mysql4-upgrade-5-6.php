<?php
/**
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    The Great Courses
 * @package     Adcoderouter
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

//line below exists to change this field to make it a type date (instead of datetime), it also sets null values correctly
$conn->modifyColumn($installer->getTable('adcoderouter/redirects'), 'start_date', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_DATE,
    'default'   => null,
    'comment'   => 'start date',
    'nullable'  => true,
));

//line below exists to change this field to make it a type date (instead of datetime), it also sets null values correctly
$conn->modifyColumn($installer->getTable('adcoderouter/redirects'), 'end_date', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_DATE,
    'default'   => null,
    'comment'   => 'end date',
    'nullable'  => true,
));

$installer->endSetup();
