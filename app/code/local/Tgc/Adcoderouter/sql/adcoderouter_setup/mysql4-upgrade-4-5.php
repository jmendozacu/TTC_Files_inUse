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
$conn->modifyColumn($installer->getTable('adcoderouter/redirects'), 'description', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'default'   => null,
    'comment'   => 'description',
    'nullable'  => true,
    'length' => 255,
));


$installer->endSetup();
