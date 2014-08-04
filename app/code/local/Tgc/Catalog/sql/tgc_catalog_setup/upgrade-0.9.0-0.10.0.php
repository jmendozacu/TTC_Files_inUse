<?php
/**
 * Create attributes
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Catalog_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'bibliography', array(
    'type'                       => 'text',
    'label'                      => 'Bibliography',
    'input'                      => 'textarea',
    'sort_order'                 => 180,
    'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'required'                   => 0,
    'visible'                    => 1,
    'searchable'                 => 1,
    'filterable'                 => 0,
    'comparable'                 => 0,
    'wysiwyg_enabled'            => true,
    'is_html_allowed_on_front'   => true,
    'apply_to'                   => 'configurable',
    'group'                      => 'General',
));

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'course_summary', array(
    'type'                       => 'text',
    'label'                      => 'Course Summary',
    'input'                      => 'textarea',
    'sort_order'                 => 185,
    'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'required'                   => 0,
    'visible'                    => 1,
    'searchable'                 => 1,
    'filterable'                 => 0,
    'comparable'                 => 0,
    'wysiwyg_enabled'            => true,
    'is_html_allowed_on_front'   => true,
    'apply_to'                   => 'configurable',
    'group'                      => 'General',
));

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'recommended_links', array(
    'type'                       => 'text',
    'label'                      => 'Recommended Links',
    'input'                      => 'textarea',
    'sort_order'                 => 190,
    'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'required'                   => 0,
    'visible'                    => 1,
    'searchable'                 => 1,
    'filterable'                 => 0,
    'comparable'                 => 0,
    'wysiwyg_enabled'            => true,
    'is_html_allowed_on_front'   => true,
    'apply_to'                   => 'configurable',
    'group'                      => 'General',
));

$installer->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'bibliography', array('is_wysiwyg_enabled' => 1));
$installer->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'course_summary', array('is_wysiwyg_enabled' => 1));
$installer->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'recommended_links', array('is_wysiwyg_enabled' => 1));

$installer->endSetup();
