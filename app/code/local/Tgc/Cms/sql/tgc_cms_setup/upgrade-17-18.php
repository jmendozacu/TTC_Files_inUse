<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Cms_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();
$conn = $installer->getConnection();

$installer->run("
    DROP TABLE IF EXISTS `{$this->getTable('cms_best_sellers')}`;
");

$entity = Mage_Catalog_Model_Product::ENTITY;
$installer->removeAttribute($entity, 'best_selling');

$attributesToInstall = array(
    'guest_bestsellers'         => 'Guest/Prospect Bestsellers',
    'authenticated_bestsellers' => 'Authenticated Bestsellers',
);
$attrIds = array();

foreach ($attributesToInstall as $code => $name) {
    $datas = array (
        'attribute_model' => null,
        'backend' => null,
        'type' => 'varchar',
        'table' => null,
        'frontend' => null,
        'input' => 'text',
        'label' => $name,
        'frontend_class' => 'validate-digits',
        'source' => null,
        'required' => 0,
        'user_defined' => 1,
        'default' => 0,
        'unique' => 0,
        'note' => 'Higher value means more popular',
        'input_renderer' => null,
        'global' => 0,
        'searchable' => 0,
        'filterable' => 0,
        'comparable' => 0,
        'visible_on_front' => 1,
        'is_html_allowed_on_front' => 1,
        'is_used_for_price_rules' => 0,
        'filterable_in_search' => 0,
        'used_for_sort_by' => 0,
        'is_configurable' => 0,
        'apply_to' => null,
        'visible_in_advanced_search' => 0,
        'position' => 0,
        'wysiwyg_enabled' => 0,
        'used_for_promo_rules' => 0,
        'search_weight' => 1,
        'option' =>
        array (
            'values' =>
            array (
            ),
        ),
    );
    $installer->removeAttribute($entity, $code);
    $installer->addAttribute($entity, $code, $datas);

    $update = array(
        'is_used_in_product_listing' => 1,
        'used_in_product_listing'    => 1,
    );
    $installer->updateAttribute($entity, $code, $update);
    $attribute = $installer->getAttribute($entity, $code);
    $attrIds[] = $attribute['attribute_id'];
}

$defaultSetId = Mage::getModel('catalog/product')
    ->getResource()
    ->getEntityType()
    ->getDefaultAttributeSetId();

$attrGroup = $installer->getAttributeGroup($entity, $defaultSetId, 'General');
$attrGroupId = ($attrGroup)
    ? $attrGroup['attribute_group_id']
    : $installer->getDefaultAttributeGroupId($entity, $defaultSetId);

$attrSetName = 'Courses';
$attrSetCourses = $installer->getAttributeSet($entity, $attrSetName);

$attrGroup = $installer->getAttributeGroup($entity, $attrSetCourses['attribute_set_id'], 'General');
$attrGroupCoursesId = ($attrGroup)
    ? $attrGroup['attribute_group_id']
    : $installer->getDefaultAttributeGroupId($entity, $attrSetCourses['attribute_set_id']);

$attrSetName = 'Sets';
$attrSetSets = $installer->getAttributeSet($entity, $attrSetName);

$attrGroup = $installer->getAttributeGroup($entity, $attrSetSets['attribute_set_id'], 'General');
$attrGroupSetsId = ($attrGroup)
    ? $attrGroup['attribute_group_id']
    : $installer->getDefaultAttributeGroupId($entity, $attrSetSets['attribute_set_id']);

foreach ($attrIds as $id) {
    $installer->addAttributeToSet($entity, $defaultSetId, $attrGroupId, $id);
    $installer->addAttributeToSet($entity, $attrSetCourses['attribute_set_id'], $attrGroupCoursesId, $id);
    $installer->addAttributeToSet($entity, $attrSetSets['attribute_set_id'], $attrGroupSetsId, $id);
}

$installer->endSetup();
