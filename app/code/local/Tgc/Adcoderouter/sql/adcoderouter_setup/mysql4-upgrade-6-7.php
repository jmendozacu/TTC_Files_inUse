<?php
/**
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    The Great Courses
 * @package     Adcoderouter
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();

$conn->addColumn($installer->getTable('adcoderouter/redirects'), 'ad_type', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'unsigned'  => true,
    'nullable'  => true,
    'default'   => null,
    'comment'   => 'ad type',
    'after'     => 'search_expression',
));

$conn->addColumn($installer->getTable('adcoderouter/redirects'), 'welcome_subtitle', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => 150,
    'default'   => null,
    'nullable'  => true,
    'comment'   => 'welcome subtitle',
    'after'     => 'pid',
));

$conn->addColumn($installer->getTable('adcoderouter/redirects'), 'more_details', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'default'   => null,
    'nullable'  => true,
    'comment'   => 'more_details',
    'after'     => 'description',
));

$installer->endSetup();