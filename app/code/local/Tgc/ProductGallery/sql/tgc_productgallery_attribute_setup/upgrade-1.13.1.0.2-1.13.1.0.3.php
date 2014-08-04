<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
$installer = $this;
$installer->startSetup();

$entity = Mage_Catalog_Model_Product::ENTITY;

//media format
$drtvTrailer = array(
    'type'                          => 'varchar',
    'label'                         => 'DRTV Trailer',
    'input'                         => 'media_image',
    'frontend'                      => 'catalog/product_attribute_frontend_image',
    'required'                      => false,
    'sort_order'                    => 2,
    'global'                        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'used_in_product_listing'       => true,
    'group'                         => 'Images',
    'searchable'                    => '0',
    'filterable'                    => '0',
    'comparable'                    => '0',
    'is_used_for_price_rules'       => '0',
    'filterable_in_search'          => '0',
    'used_for_sort_by'              => '0',
    'visible_in_advanced_search'    => '0',
    'used_for_promo_rules'          => '0',
);

$this->addAttribute($entity, 'drtv_trailer', $drtvTrailer);


$installer->endSetup();