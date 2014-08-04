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

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'professor', array (
    'backend'    => 'profs/professor_attribute_backend',
    'type'       => 'varchar',
    'input'      => 'multiselect',
    'label'      => 'Professors',
    'source'     => 'profs/professor_attribute_source',
    'required'   => 0,
    'visible'    => 1,
    'searchable' => 1,
    'filterable' => 1,
    'comparable' => 1,
    'visible_on_front' => 1,
    'filterable_in_search' => 2,
    'used_in_product_listing' => 0,
    'visible_in_advanced_search' => 1,
));

$installer->endSetup();
