<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
$installer = $this;
$installer->startSetup();

$collection = Mage::getModel('catalog/product')->getCollection()
    ->addAttributeToFilter('attribute_set_id', '12')
    ->addAttributeToFilter('status', '0');

if (count($collection) > 0) {
    $i = 0;
    foreach($collection as $product) {
        $i++;
        $product->setStatus(1);
        $product->getResource()->saveAttribute($product, 'status');
    }
}

$installer->endSetup();