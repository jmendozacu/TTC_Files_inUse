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

$entity = Mage_Catalog_Model_Product::ENTITY;

$codesToRemove = array('is_set', 'university', 'lecture_length');
foreach ($codesToRemove as $code) {
    $installer->removeAttribute($entity, $code);
}
