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

$conn->addColumn($installer->getTable('adcoderouter/redirects'), 'dax_key', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'unsigned'  => true,
    'nullable'  => true,
    'default'   => null,
    'comment'   => 'dax key',
    'after'     => 'search_expression',
));

$installer->endSetup();