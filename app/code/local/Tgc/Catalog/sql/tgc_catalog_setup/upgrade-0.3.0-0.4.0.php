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
//special_price update
$this->updateAttribute($entity, 'special_price', $data);

$data   = array(
    'used_for_promo_rules' => '0',
    'is_used_for_promo_rules' => '0',
);
//clearance flag update
$this->updateAttribute($entity, 'clearance_flag', $data);

$installer->endSetup();
