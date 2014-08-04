<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_DigitalLibrary_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$entity = Mage_Catalog_Model_Product::ENTITY;
$streaming = array(
    'label'                         => 'Availability of Streaming',
    'input'                         => 'select',
    'default'                       => Tgc_DigitalLibrary_Model_Source_Streaming::BOTH,
    'type'                          => 'int',
    'source'                        => 'tgc_dl/source_streaming',
    'global'                        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'required'                      => false,
    'is_visible'                    => false,
    'is_searchable'                 => false,
    'is_filterable'                 => true,
    'unique'                        => false,
    'is_comparable'                 => false,
    'is_visible_on_front'           => true,
    'is_html_allowed_on_front'      => false,
    'user_defined'                  => true,
    'is_configurable'               => false,
    'is_used_for_promo_rules'       => false,
    'is_visible_in_advanced_search' => false,
    'used_in_product_listing'       => true,
    'used_for_sort_by'              => false,
    'is_filterable_in_search'       => true,
    'apply_to'                      => 'configurable',
);

$installer->addAttribute($entity, 'availability_of_streaming', $streaming);

$attr = $installer->getAttribute($entity, 'availability_of_streaming');
$attrId = $attr['attribute_id'];

$attrSetName = 'Courses';
$attrSetCourses = $installer->getAttributeSet($entity, $attrSetName);
$attrGroup = $installer->getAttributeGroup($entity, $attrSetCourses['attribute_set_id'], 'General');
$attrGroupCoursesId = ($attrGroup)
    ? $attrGroup['attribute_group_id']
    : $installer->getDefaultAttributeGroupId($entity, $attrSetCourses['attribute_set_id']);

$installer->addAttributeToSet($entity, $attrSetCourses['attribute_set_id'], $attrGroupCoursesId, $attrId);

$installer->endSetup();
