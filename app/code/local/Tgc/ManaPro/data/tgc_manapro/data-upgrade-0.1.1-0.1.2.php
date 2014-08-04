<?php
/**
 * Tgc_ManaPro
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     ManaPro
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();

try {
$filter = Mage::getModel('mana_filters/filter2')->getCollection()->addCodeFilter('media_format')->getFirstItem();
Mage::helper('mana_db')->updateDefaultableField(
    $filter,
    'name',
    Mana_Filters_Resource_Filter2::DM_NAME,
    array('name' => 'All Filters'),
    0
);
$filter->save();
} catch (Exception $e) {
    Mage::logException($e);
}

try {
$filter = Mage::getModel('mana_filters/filter2')->getCollection()->addCodeFilter('price')->getFirstItem();
Mage::helper('mana_db')->updateDefaultableField(
    $filter,
    'name',
    Mana_Filters_Resource_Filter2::DM_NAME,
    array('name' => 'All Price Ranges'),
    0
);
$filter->save();
} catch (Exception $e) {
    Mage::logException($e);
}

try {
$filter = Mage::getModel('mana_filters/filter2')->getCollection()->addCodeFilter('category')->getFirstItem();
Mage::helper('mana_db')->updateDefaultableField(
    $filter,
    'is_enabled',
    Mana_Filters_Resource_Filter2::DM_IS_ENABLED,
    array('is_enabled' => 0),
    0
);
$filter->save();
} catch (Exception $e) {
    Mage::logException($e);
}

$installer->setConfigData('mana_filters/display/show_more_item_count', 20);

$installer->endSetup();
