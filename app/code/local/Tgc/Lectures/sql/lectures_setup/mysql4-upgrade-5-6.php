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

//line below exists to change this field to allow it to accept null values because this field is not required.
$conn->modifyColumn($installer->getTable('lectures/lectures'), 'duration', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'default'   => 0,
    'comment'   => 'duration',
    'nullable'  => false,
    'unsigned'  => true,
));

$conn->modifyColumn($installer->getTable('lectures/lectures'), 'lecture_number', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'default'   => 0,
    'comment'   => 'lecture number',
    'nullable'  => false,
    'unsigned'  => true,
));

$conn->modifyColumn($installer->getTable('lectures/lectures'), 'title', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => 255,
    'default'   => '',
    'nullable'  => false,
    'comment'   => 'title',
));

$conn->modifyColumn($installer->getTable('lectures/lectures'), 'default_course_number', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => 255,
    'default'   => '',
    'nullable'  => false,
    'comment'   => 'course id',
));

$installer->endSetup();
