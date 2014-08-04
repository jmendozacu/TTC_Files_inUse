<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Cms_Model_Resource_Setup */
/* @var $conn Varien_Db_Adapter_Interface */
$installer = $this;
$installer->startSetup();
$conn = $installer->getConnection();

$entity = Mage_Catalog_Model_Product::ENTITY;
$attributesToUpdate = array(
    'guest_bestsellers',
    'authenticated_bestsellers',
);

foreach ($attributesToUpdate as $code) {
    $update = array(
        'note' => 'Lower value means more popular',
    );
    $installer->updateAttribute($entity, $code, $update);
}

$installer->endSetup();
