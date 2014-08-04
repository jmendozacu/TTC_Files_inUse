<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Datamart_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$entity = Mage_Catalog_Model_Category::ENTITY;
$installer->addAttribute($entity, 'subject_id', array(
        'label'      => 'Subject ID',
        'group'      => 'General Information',
        'type'       => 'int',
        'input'      => 'text',
        'required'   => false,
        'sort_order' => 3,
        'global'     => false,
    )
);

$installer->endSetup();
