<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/* @var $installer Tgc_Catalog_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$mediaFormatAttribute = $installer->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'media_format');
if ($mediaFormatAttribute) {
    $optionCollection = Mage::getModel('eav/entity_attribute_option')->getCollection()
        ->setAttributeFilter($mediaFormatAttribute['attribute_id'])
        ->setStoreFilter(0, false);
    foreach ($optionCollection as $option) {
        switch ($option->getValue()) {
            case 'Video Download':
                $option->setSortOrder(1);
                break;
            case 'Audio Download':
                $option->setSortOrder(2);
                break;
            case 'DVD':
                $option->setSortOrder(3);
                break;
            case 'CD':
                $option->setSortOrder(4);
                break;
            default:
                $option->setSortOrder($option->getSortOrder()+5);
                break;
        }
        $option->save();
    }
}

$installer->endSetup();
