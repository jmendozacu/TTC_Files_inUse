<?php
/**
 * User: mhidalgo
 * Date: 27/03/14
 * Time: 10:32
 */

try {
    $filter = Mage::getModel('mana_filters/filter2')->getCollection()->addCodeFilter('media_format')->getFirstItem();
    Mage::helper('mana_db')->updateDefaultableField(
        $filter,
        'name',
        Mana_Filters_Resource_Filter2::DM_NAME,
        array('name' => 'All Formats'),
        0
    );
    $filter->save();
} catch (Exception $e) {
    Mage::logException($e);
}