<?php
/**
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    The Great Courses
 * @package     Tgc_Customer
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;
$installer->startSetup();

//Customer profile only saves a non-static attribute if is following condition is NOT true (customer_eav_attribute.is_system = 1 AND customer_eav_attribute.is_visible = 0)
//Therefore, I'm setting is_visible to 1 to prevent that condition from excluding attribute from being saved during import.
$eavAttributeConfig = Mage::getModel('eav/config');
$attribute = $eavAttributeConfig->getAttribute('customer', 'dax_address_record');
$attribute->setData('is_visible', 1);
$attribute->save();

$installer->endSetup();


