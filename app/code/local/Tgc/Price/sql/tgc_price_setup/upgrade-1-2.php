<?php
/**
 * Creates a table to store add code that is associated with the URL
 * 
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    The Great Courses
 * @package     Price
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$conn->addColumn($installer->getTable('enterprise_urlrewrite/url_rewrite'), 'ad_code', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'length'    => null,
    'default'   => null,
    'comment'   => 'Ad code',
));

$installer->endSetup();
