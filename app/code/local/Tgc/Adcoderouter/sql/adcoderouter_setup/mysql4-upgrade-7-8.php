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

//line below modifies ad_type so that it is NOT nullable, default value is zero. 0 corresponds to ad type of NONE
$conn->modifyColumn($installer->getTable('adcoderouter/redirects'), 'ad_type', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'unsigned'  => true,
    'nullable'  => false,
    'default'   => 0,
    'comment'   => 'ad type',
    'after'     => 'search_expression',
));

$installer->endSetup();
