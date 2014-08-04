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

$locations[] = array('location' =>'Atlanta', 'location_code' => 'atlanta', 'sort_order' => 5);
$locations[] = array('location' =>'Boston', 'location_code' => 'boston', 'sort_order' => 10);
$locations[] = array('location' =>'New York', 'location_code' => 'new-york', 'sort_order' => 15);
$locations[] = array('location' =>'Chicago', 'location_code' => 'chicago', 'sort_order' => 20);
$locations[] = array('location' =>'Philadelphia', 'location_code' => 'philadelphia', 'sort_order' => 25);
$locations[] = array('location' =>'Phoenix', 'location_code' => 'phoenix', 'sort_order' => 30);
$locations[] = array('location' =>'Dallas/Fort Worth', 'location_code' => 'dallas-ft_worth', 'sort_order' => 35);
$locations[] = array('location' =>'Portland', 'location_code' => 'portland', 'sort_order' => 40);
$locations[] = array('location' =>'Denver', 'location_code' => 'denver', 'sort_order' => 45);
$locations[] = array('location' =>'San Antonio/Austin', 'location_code' => 'san_antonio-austin', 'sort_order' => 50);
$locations[] = array('location' =>'Detroit', 'location_code' => 'detroit', 'sort_order' => 55);
$locations[] = array('location' =>'San Diego', 'location_code' => 'san-diego', 'sort_order' => 60);
$locations[] = array('location' =>'Houston', 'location_code' => 'houston', 'sort_order' => 65);
$locations[] = array('location' =>'San Francisco', 'location_code' => 'san-francisco', 'sort_order' => 70);
$locations[] = array('location' =>'Los Angeles', 'location_code' => 'los-angeles', 'sort_order' => 75);
$locations[] = array('location' =>'Seattle', 'location_code' => 'seattle', 'sort_order' => 80);
$locations[] = array('location' =>'Miami', 'location_code' => 'miami', 'sort_order' => 85);
$locations[] = array('location' =>'St. Louis', 'location_code' => 'st-louis', 'sort_order' => 90);
$locations[] = array('location' =>'Minneapolis/St. Paul', 'location_code' => 'minneapolis-st_paul', 'sort_order' => 95);

$conn->insertMultiple($installer->getTable('tgc_events/locations'), $locations);

$installer->endSetup();
