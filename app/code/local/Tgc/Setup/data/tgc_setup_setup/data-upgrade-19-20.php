<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Setup_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$installer->setConfigData(
    Enterprise_GiftCard_Model_Giftcard::XML_PATH_ORDER_ITEM_STATUS,
    Mage_Sales_Model_Order_Item::STATUS_PENDING
);

$installer->endSetup();
