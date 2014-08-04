<?php
/**
 * Modify some product attributes
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Catalog_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$entity = Mage_Catalog_Model_Product::ENTITY;

$data   = array(
    'filterable' => '1',
    'is_filterable' => '1',
    'position' => 10,
);
//course type code
$this->updateAttribute($entity, 'course_type_code', $data);

$data   = array(
    'filterable' => '1',
    'is_filterable' => '1',
    'position' => 20,
);
//media format
$this->updateAttribute($entity, 'media_format', $data);

$data   = array(
    'position' => 30,
);
//price
$this->updateAttribute($entity, 'price', $data);

$data   = array(
    'filterable' => '1',
    'is_filterable' => '1',
    'position' => 40,
);
//average rating
$this->updateAttribute($entity, 'average_rating', $data);

$installer->endSetup();
