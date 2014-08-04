<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     CmsSetup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
/* @var $installer Guidance_CmsSetup_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */

$installer->startSetup();

$conn->addColumn($installer->getTable('cms/block'), 'hash', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => 64,
    'nullable'  => true,
    'default'   => null,
    'comment'   => 'Hash of Guidance_CmsSetup',
));


$conn->addColumn($installer->getTable('cms/page'), 'hash', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 64,
        'nullable'  => true,
        'default'   => null,
        'comment'   => 'Hash of Guidance_CmsSetup',
));


$installer->endSetup();