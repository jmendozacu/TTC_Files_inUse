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

$conn->modifyColumn($installer->getTable('adcoderouter/redirects'), 'ad_code', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'default'   => null,
    'comment'   => 'ad code',
    'unsigned'  => true,
    'nullable'  => true,
    'default'   => null,
));

$conn->modifyColumn($installer->getTable('adcoderouter/redirects'), 'course_id', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'default'   => null,
    'comment'   => 'course_id',
    'unsigned'  => true,
    'nullable'  => true,
    'default'   => null,
));

$conn->modifyColumn($installer->getTable('adcoderouter/redirects'), 'professor_id', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'default'   => null,
    'comment'   => 'professor id',
    'unsigned'  => true,
    'nullable'  => true,
    'default'   => null,
));

$conn->modifyColumn($installer->getTable('adcoderouter/redirects'), 'category_id', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'default'   => null,
    'comment'   => 'category id',
    'unsigned'  => true,
    'nullable'  => true,
    'default'   => null,
));

$conn->modifyColumn($installer->getTable('adcoderouter/redirects'), 'cms_page_id', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'default'   => null,
    'comment'   => 'cms id',
    'unsigned'  => true,
    'nullable'  => true,
    'default'   => null,
));


$installer->endSetup();
