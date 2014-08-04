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
    'used_for_promo_rules' => '1',
    'is_used_for_promo_rules' => '1',
);

//course parts update
$this->updateAttribute($entity, 'course_parts', $data);

//clearance flag update
$this->updateAttribute($entity, 'clearance_flag', $data);

//course type code update
$this->updateAttribute($entity, 'course_type_code', $data);

//course id update
$this->updateAttribute($entity, 'course_id', $data);

//media format
$this->updateAttribute($entity, 'media_format', $data);

$installer->endSetup();
