<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();


$categories = Mage::getModel('catalog/category')
    ->getCollection()
    ->addAttributeToSelect('*')
    ->addAttributeToFilter('is_anchor', 0)
    ->addAttributeToFilter('entity_id', array("gt" => 1))
    ->setOrder('entity_id')
;

foreach($categories as $category) {
    $category->setIsAnchor(1);
    $category->save();
    $_categories = $category->getChildrenCategories();
    foreach($_categories as $_category){
        $category->setIsAnchor(1);
        $category->save();
    }
}


$installer->endSetup();

