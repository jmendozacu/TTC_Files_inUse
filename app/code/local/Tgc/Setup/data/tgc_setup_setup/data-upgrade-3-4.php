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

Mage::helper('tgc_price')
    ->getFreeShippingRule()
    ->setData('website_ids', array(
    	$installer->getUsWebsite()->getId(),
    	$installer->getUkWebsite()->getId(),
    	$installer->getAuWebsite()->getId(),
    ))
    ->save(); 

$installer->endSetup();