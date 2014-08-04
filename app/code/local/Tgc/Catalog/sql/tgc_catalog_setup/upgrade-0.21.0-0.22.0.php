<?php
/**
 * Modify course id
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Catalog_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$entity = Mage_Catalog_Model_Product::ENTITY;
$data   = array(
    'required' => '1',
    'is_required' => '1',
);
$this->updateAttribute($entity, 'course_id', $data);

//update partner images
$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToSelect('partner')
    ->addAttributeToFilter('partner', array('notnull' => true))
    ->addAttributeToFilter('partner', array('neq' => ''));

$action = Mage::getModel('catalog/product_action');
foreach ($collection as $product) {
    $partner = $product->getPartner();
    if (empty($partner)) {
        continue;
    }
    switch (basename($partner)) {
        case 'nationalgeographic.jpg':
            $image = 'partner-logo1.png';
            break;
        default:
            $image = 'partner-logo2.png';
    }

    $productData = array('partner' => $image);
    try {
        $action->updateAttributes(array($product->getId()), $productData, 0);
    } catch (Exception $e) {}
}

$installer->endSetup();
