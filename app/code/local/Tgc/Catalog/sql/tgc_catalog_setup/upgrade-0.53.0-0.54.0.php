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
$installer->startSetup();

$installer->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'set_members', 'used_in_product_listing', 1);

$installer->endSetup();
