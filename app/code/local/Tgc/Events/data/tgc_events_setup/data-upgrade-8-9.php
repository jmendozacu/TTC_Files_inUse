<?php
/**
 * Locations setup
 * 
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
$installer = $this;
 
$installer->startSetup();

$locations[] = array('location' =>'All', 'location_code' => 'all', 'sort_order' => 1, 'is_active' => 1);

$conn->insertMultiple($installer->getTable('tgc_events/locations'), $locations);

$installer->endSetup();
