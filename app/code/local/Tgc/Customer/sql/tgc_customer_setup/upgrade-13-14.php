<?php
/**
 * @category    TGC
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance
 * @author      Guidance Magento Team <magento@guidance.com>
 */

/* @var $installer Tgc_Customer_Model_Resource_Setup */
$installer = $this;

$attribute = Mage::getSingleton('eav/config')->getAttribute($installer->getEntityTypeId('customer'), 'web_user_id');
$attribute->setData('used_in_forms', array());
$attribute->save();
