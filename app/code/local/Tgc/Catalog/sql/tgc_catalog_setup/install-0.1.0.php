<?php
/**
 * Install product attributes
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Catalog_Model_Resource_Setup */
$installer = $this;

$entity = Mage_Catalog_Model_Product::ENTITY;
$attrCodes = array();
$attrIds = array();

//course id
$courseId = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'varchar',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'text',
    'label' => 'Course Id',
    'frontend_class' => NULL,
    'source' => NULL,
    'required' => '0',
    'user_defined' => '1',
    'default' => NULL,
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '1',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '0',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '1',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => NULL,
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
        ),
    ),
);
$this->addAttribute($entity, 'course_id', $courseId);
$attrCodes[] = 'course_id';

//copyright year
$copyrightYear = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'varchar',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'text',
    'label' => 'Copyright Year',
    'frontend_class' => NULL,
    'source' => NULL,
    'required' => '0',
    'user_defined' => '1',
    'default' => NULL,
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '1',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => NULL,
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
        ),
    ),
);
$this->addAttribute($entity, 'copyright_year', $copyrightYear);
$attrCodes[] = 'copyright_year';

//content length
$contentLength = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'varchar',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'text',
    'label' => 'Content Length',
    'frontend_class' => NULL,
    'source' => NULL,
    'required' => '0',
    'user_defined' => '1',
    'default' => NULL,
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '0',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => 'simple',
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
        ),
    ),
);
$this->addAttribute($entity, 'content_length', $contentLength);
$attrCodes[] = 'content_length';

//publish date
$publishDate = array (
    'attribute_model' => NULL,
    'backend' => 'eav/entity_attribute_backend_datetime',
    'type' => 'datetime',
    'table' => NULL,
    'frontend' => 'eav/entity_attribute_frontend_datetime',
    'input' => 'date',
    'label' => 'Publish Date',
    'frontend_class' => NULL,
    'source' => NULL,
    'required' => '0',
    'user_defined' => '1',
    'default' => NULL,
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '0',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => NULL,
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
        ),
    ),
);
$this->addAttribute($entity, 'publish_date', $publishDate);
$attrCodes[] = 'publish_date';

//course parts
$courseParts = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'varchar',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'text',
    'label' => 'Course Parts',
    'frontend_class' => NULL,
    'source' => NULL,
    'required' => '0',
    'user_defined' => '1',
    'default' => NULL,
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '0',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => NULL,
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
        ),
    ),
);
$this->addAttribute($entity, 'course_parts', $courseParts);
$attrCodes[] = 'course_parts';

//lecture length
$lectureLength = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'varchar',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'text',
    'label' => 'Lecture Length',
    'frontend_class' => NULL,
    'source' => NULL,
    'required' => '0',
    'user_defined' => '1',
    'default' => NULL,
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '0',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => NULL,
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
        ),
    ),
);
$this->addAttribute($entity, 'lecture_length', $lectureLength);
$attrCodes[] = 'lecture_length';

//num_lecture
$numLecture = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'varchar',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'text',
    'label' => 'Number of Lectures',
    'frontend_class' => NULL,
    'source' => NULL,
    'required' => '0',
    'user_defined' => '1',
    'default' => NULL,
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '0',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => NULL,
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
        ),
    ),
);
$this->addAttribute($entity, 'num_lecture', $numLecture);
$attrCodes[] = 'num_lecture';

//course type code
$courseTypeCode = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'varchar',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'text',
    'label' => 'Course Type Code',
    'frontend_class' => NULL,
    'source' => NULL,
    'required' => '0',
    'user_defined' => '1',
    'default' => NULL,
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '1',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => NULL,
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
        ),
    ),
);
$this->addAttribute($entity, 'course_type_code', $courseTypeCode);
$attrCodes[] = 'course_type_code';

//primary subject
$primarySubject = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'varchar',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'text',
    'label' => 'Primary Subject',
    'frontend_class' => NULL,
    'source' => NULL,
    'required' => '0',
    'user_defined' => '1',
    'default' => NULL,
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '0',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => NULL,
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
        ),
    ),
);
$this->addAttribute($entity, 'primary_subject', $primarySubject);
$attrCodes[] = 'primary_subject';

//clearance flag
$clearanceFlag = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'int',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'boolean',
    'label' => 'Clearance Flag',
    'frontend_class' => NULL,
    'source' => 'eav/entity_attribute_source_boolean',
    'required' => '0',
    'user_defined' => '1',
    'default' => '0',
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '0',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => NULL,
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '1',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
        ),
    ),
);
$this->addAttribute($entity, 'clearance_flag', $clearanceFlag);
$attrCodes[] = 'clearance_flag';

//package subject
$packageSubject = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'varchar',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'text',
    'label' => 'Package Subject',
    'frontend_class' => NULL,
    'source' => NULL,
    'required' => '0',
    'user_defined' => '1',
    'default' => NULL,
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '0',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => NULL,
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
        ),
    ),
);
$this->addAttribute($entity, 'package_subject', $packageSubject);
$attrCodes[] = 'package_subject';

//professor information
$professorInformation = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'varchar',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'text',
    'label' => 'Professor Information',
    'frontend_class' => NULL,
    'source' => NULL,
    'required' => '0',
    'user_defined' => '1',
    'default' => NULL,
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '0',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => NULL,
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
        ),
    ),
);
$this->addAttribute($entity, 'professor_information', $professorInformation);
$attrCodes[] = 'professor_information';

//university
$university = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'varchar',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'text',
    'label' => 'University',
    'frontend_class' => NULL,
    'source' => NULL,
    'required' => '0',
    'user_defined' => '1',
    'default' => NULL,
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '0',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => NULL,
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
        ),
    ),
);
$this->addAttribute($entity, 'university', $university);
$attrCodes[] = 'university';

//media format
$mediaFormat = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'int',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'select',
    'label' => 'Media Format',
    'frontend_class' => NULL,
    'source' => 'eav/entity_attribute_source_table',
    'required' => '0',
    'user_defined' => '1',
    'default' => '',
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '1',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '1',
    'apply_to' => 'simple',
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
            0 => 'DVD',
            1 => 'CD',
            2 => 'Audio Download',
            3 => 'Video Download',
            4 => 'CD Soundtrack',
            5 => 'Soundtrack Download',
        ),
    ),
);
$this->addAttribute($entity, 'media_format', $mediaFormat);
$attrCodes[] = 'media_format';

//free streaming
$freeStreaming = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'int',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'boolean',
    'label' => 'Free Streaming',
    'frontend_class' => NULL,
    'source' => 'eav/entity_attribute_source_boolean',
    'required' => '0',
    'user_defined' => '1',
    'default' => '0',
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '0',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => NULL,
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
        ),
    ),
);
$this->addAttribute($entity, 'free_streaming', $freeStreaming);
$attrCodes[] = 'free_streaming';

//guidebook
$guidebook = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'varchar',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'text',
    'label' => 'Guidebook',
    'frontend_class' => NULL,
    'source' => NULL,
    'required' => '0',
    'user_defined' => '1',
    'default' => NULL,
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '0',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => NULL,
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
        ),
    ),
);
$this->addAttribute($entity, 'guidebook', $guidebook);
$attrCodes[] = 'guidebook';

//is set
$isSet = array (
    'attribute_model' => NULL,
    'backend' => NULL,
    'type' => 'int',
    'table' => NULL,
    'frontend' => NULL,
    'input' => 'boolean',
    'label' => 'Is Set',
    'frontend_class' => NULL,
    'source' => 'eav/entity_attribute_source_boolean',
    'required' => '0',
    'user_defined' => '1',
    'default' => '0',
    'unique' => '0',
    'note' => NULL,
    'input_renderer' => NULL,
    'global' => '1',
    'visible' => '1',
    'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '0',
    'is_html_allowed_on_front' => '1',
    'is_used_for_price_rules' => '0',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => NULL,
    'visible_in_advanced_search' => '0',
    'position' => '0',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '0',
    'search_weight' => '1',
    'option' =>
    array (
        'values' =>
        array (
        ),
    ),
);
$this->addAttribute($entity, 'is_set', $isSet);
$attrCodes[] = 'is_set';

foreach ($attrCodes as $code) {
    $attr = $installer->getAttribute($entity, $code);
    $attrIds[] = $attr['attribute_id'];
}

$attrSetName = 'Courses';
$attrSet = $installer->getAttributeSet($entity, $attrSetName);
if (!isset($attrSet['attribute_set_id'])) {
    $defaultSetId =  Mage::getModel('catalog/product')
        ->getResource()
        ->getEntityType()
        ->getDefaultAttributeSetId();
    $installer->copyAttributeSetId($attrSetName, $defaultSetId);
    $attrSet = $installer->getAttributeSet($entity, $attrSetName);
}

$attrGroup = $installer->getAttributeGroup($entity, $attrSet['attribute_set_id'], 'General');
$attrGroupId = ($attrGroup)
    ? $attrGroup['attribute_group_id']
    : $installer->getDefaultAttributeGroupId($entity, $attrSetId);

foreach ($attrIds as $id) {
    $installer->addAttributeToSet($entity, $attrSet['attribute_set_id'], $attrGroupId, $id);
}
