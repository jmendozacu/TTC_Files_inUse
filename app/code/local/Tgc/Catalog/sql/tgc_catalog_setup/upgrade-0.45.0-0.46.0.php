<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
$installer = $this;
$installer->startSetup();

$entity = Mage_Catalog_Model_Product::ENTITY;
//$installer->removeAttribute($entity, 'guidebook');

$installer->endSetup();
