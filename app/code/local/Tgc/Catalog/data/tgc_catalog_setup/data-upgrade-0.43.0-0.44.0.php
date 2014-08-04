<?php
/**
 * User: mhidalgo
 * Date: 09/04/14
 * Time: 09:02
 */

/* @var $installer Tgc_Setup_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$collection = Mage::getModel('catalog/category')->getCollection()
    ->addAttributeToSelect('is_anchor')
    ->addAttributeToFilter('is_anchor',array('eq' => 0));

foreach ($collection as $category) {
    $category->setIsAnchor(1);
    $category->save();
}

$installer->endSetup();