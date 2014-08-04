<?php
/**
 * ProductGallery
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     ProductGallery
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer  = $this;

$installer->getConnection()->addColumn(
    $installer->getTable('catalog/product_attribute_media_gallery_value'),
    'brightcove_id',
    array(
        'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
        'lenght'  => 64,
        'comment' => 'Brightcove Video Id'
    )
);
