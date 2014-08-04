<?php
/**
 * Cms additions - adding column to cms_page table stores whether page headline should be visible.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer  = $this;

$conn->addColumn(
    $installer->getTable('cms/page'),
    'page_title_is_page_headline',
    array(
        'type'          => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
        'nullable'      => false,
        'default'       => 1,
        'comment'       => 'Is headline visible?',
        'after'         =>  'title',
    )
);
