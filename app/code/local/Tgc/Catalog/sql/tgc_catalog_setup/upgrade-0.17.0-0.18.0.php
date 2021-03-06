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

$data = array(
    'searchable'    => 1,
    'is_searchable' => 1,
);
$installer->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'professor', $data);

$installer->endSetup();
