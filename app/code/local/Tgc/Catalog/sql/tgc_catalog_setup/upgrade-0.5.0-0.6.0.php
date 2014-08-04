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
$installer = $this;
$installer->startSetup();

$entity = Mage_Catalog_Model_Product::ENTITY;

//monthly free lecture from date
$attr = array (
    'type'                       => 'datetime',
    'label'                      => 'Monthly Free Lecture From Date',
    'input'                      => 'date',
    'backend'                    => 'eav/entity_attribute_backend_datetime',
    'required'                   => false,
    'sort_order'                 => 130,
    'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'group'                      => 'General',
);
$this->addAttribute($entity, 'monthly_free_lecture_from', $attr);

//monthly free lecture to date
$attr = array (
    'type'                       => 'datetime',
    'label'                      => 'Monthly Free Lecture To Date',
    'input'                      => 'date',
    'backend'                    => 'eav/entity_attribute_backend_datetime',
    'required'                   => false,
    'sort_order'                 => 131,
    'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'group'                      => 'General',
);
$this->addAttribute($entity, 'monthly_free_lecture_to', $attr);

//marketing free lecture from date
$attr = array (
    'type'                       => 'datetime',
    'label'                      => 'Marketing Free Lecture From Date',
    'input'                      => 'date',
    'backend'                    => 'eav/entity_attribute_backend_datetime',
    'required'                   => false,
    'sort_order'                 => 140,
    'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'group'                      => 'General',
);
$this->addAttribute($entity, 'marketing_free_lecture_from', $attr);

//marketing free lecture to date
$attr = array (
    'type'                       => 'datetime',
    'label'                      => 'Marketing Free Lecture To Date',
    'input'                      => 'date',
    'backend'                    => 'eav/entity_attribute_backend_datetime',
    'required'                   => false,
    'sort_order'                 => 141,
    'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'group'                      => 'General',
);
$this->addAttribute($entity, 'marketing_free_lecture_to', $attr);

$installer->endSetup();
