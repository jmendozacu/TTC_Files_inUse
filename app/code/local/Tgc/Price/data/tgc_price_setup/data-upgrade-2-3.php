<?php
/**
 * Stores currency setup
 * 
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

Mage::getModel('salesrule/rule')
    ->addData(array (
        'name' => Tgc_Price_Helper_Data::FREE_SHIPPING_RULE_NAME,
        'uses_per_customer' => '0',
        'is_active' => '1',
        'conditions_serialized' => 'a:6:{s:4:"type";s:32:"salesrule/rule_condition_combine";s:9:"attribute";N;s:8:"operator";N;s:5:"value";s:1:"1";s:18:"is_value_processed";N;s:10:"aggregator";s:3:"all";}',
        'actions_serialized' => 'a:6:{s:4:"type";s:40:"salesrule/rule_condition_product_combine";s:9:"attribute";N;s:8:"operator";N;s:5:"value";s:1:"1";s:18:"is_value_processed";N;s:10:"aggregator";s:3:"all";}',
        'stop_rules_processing' => '0',
        'is_advanced' => '1',
        'sort_order' => '0',
        'simple_action' => 'by_percent',
        'discount_amount' => '0.0000',
        'discount_step' => '0',
        'simple_free_shipping' => '2',
        'apply_to_shipping' => '0',
        'times_used' => '0',
        'is_rss' => '0',
        'coupon_type' => '1',
        'use_auto_generation' => '0',
        'customer_group_ids' => array (),
        'website_ids' => array (),
    ))
    ->save();

$installer->endSetup();