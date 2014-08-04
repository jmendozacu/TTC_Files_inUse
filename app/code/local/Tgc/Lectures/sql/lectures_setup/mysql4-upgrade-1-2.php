<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();

$conn->addColumn($installer->getTable('lectures/lectures'), 'default_course_number', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => 255,
    'default'   => null,
    'nullable'  => true,
    'comment'   => 'course id',
    'after'     => 'description',
));

$installer->endSetup();
