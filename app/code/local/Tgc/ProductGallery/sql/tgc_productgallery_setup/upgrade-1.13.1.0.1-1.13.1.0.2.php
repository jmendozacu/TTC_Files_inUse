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

$conn->addColumn(
    $installer->getTable('catalog/product_attribute_media_gallery_value'),
    'drtv_testimonial',
    array(
        'type'    => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'lenght'  => 64,
        'comment' => 'DRTV Testimonial'
    )
);
