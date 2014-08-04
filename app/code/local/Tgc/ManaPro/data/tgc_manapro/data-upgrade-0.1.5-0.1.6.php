<?php
/**
 * User: mhidalgo
 * Date: 28/03/14
 * Time: 15:17
 */

try {
    $filter = Mage::getModel('mana_filters/filter2')->getCollection()->addCodeFilter('all_types')->getFirstItem();
    Mage::helper('mana_db')->updateDefaultableField(
        $filter,
        'name',
        Mana_Filters_Resource_Filter2::DM_NAME,
        array('name' => 'All Types'),
        0
    );
    $filter->save();
} catch (Exception $e) {
    Mage::logException($e);
}