<?php
/**
 * Locations setup
 * 
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
$installer = $this;
 
$installer->startSetup();

$types[] = array('type' =>'Book Release/Signing/Reading', 'sort_order' => 5);
$types[] = array('type' =>'Film', 'sort_order' => 10);
$types[] = array('type' =>'Exhibit', 'sort_order' => 15);
$types[] = array('type' =>'Planetarium', 'sort_order' => 20);
$types[] = array('type' =>'Play', 'sort_order' => 25);
$types[] = array('type' =>'Lecture', 'sort_order' => 30);
$types[] = array('type' =>'Discussion/Talk', 'sort_order' => 35);
$types[] = array('type' =>'Tour', 'sort_order' => 40);
$types[] = array('type' =>'Special Event', 'sort_order' => 45);
$types[] = array('type' =>'Concert', 'sort_order' => 50);
$types[] = array('type' =>'Performance', 'sort_order' => 55);
$types[] = array('type' =>'Open House', 'sort_order' => 60);
$types[] = array('type' =>'Class', 'sort_order' => 65);

$conn->insertMultiple($installer->getTable('tgc_events/types'), $types);

$installer->endSetup();
