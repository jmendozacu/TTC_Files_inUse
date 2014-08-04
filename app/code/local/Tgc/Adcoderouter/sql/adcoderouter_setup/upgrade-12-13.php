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

$conn->addColumn($installer->getTable('adcoderouter/redirects'), 'ad_code_from_param', array(
    'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'   => 60,
    'comment'  => 'Request parameter with ad code',
));

$installer->endSetup();
